<?php namespace Wynum\Rest;

use function GuzzleHttp\json_decode;

require 'vendor/autoload.php';

class Client
{

    private $secret;
    private $token;
    private $schema_url;
    private $data_url;
    private $http_client;

    public $identifier;
    public $schemas;

    public function __construct($secret, $token)
    {
        $this->token = $token;
        $this->secret = $secret;

        $this->schema_url = "https://api.wynum.com/component/{$token}?secret_key={$secret}";
        $this->data_url = "https://api.wynum.com/data/{$token}?secret_key={$secret}";

        $this->http_client = new \GuzzleHttp\Client();
    }

    public function getSchema()
    {
        $response = $this->http_client->request('GET', $this->schema_url);
        $data = json_decode($response->getBody(), true);
        $this->validateData($data);
        $this->identifier = $data['identifer'];
        $schema_json_list = $data['components'];

        $this->schemas = array_map(
            function ($json) {return new Schema($json);},
            $schema_json_list
        );
        return $this->schemas;
    }

    public function getData($args = [])
    {
        $args = $this->validateAndParseArgs($args);
        $args['secret_key'] = $this->secret;
        $response = $this->http_client->request('GET', $this->data_url, ['query' => $args]);
        $response_data = json_decode($response->getBody(), true);
        $this->validateData($response_data);
        return $response_data;
    }

    public function postData($data)
    {
        if ($this->hasFiles($data)) {
            $form_body = $this->getMultipartArray($data);
            $response = $this
                ->http_client
                ->request('POST', $this->data_url, ['multipart' => $form_body]);
        } else {
            $response = $this->http_client->request('POST', $this->data_url, ['json' => $data]);
        }

        $response_data = json_decode($response->getBody(), true);
        $this->validateData($response_data);
        return $response_data;
    }

    public function updateData($data)
    {
        if ($this->hasFiles($data)) {
            $form_body = $this->getMultipartArray($data);
            $response = $this
                ->http_client
                ->request('PUT', $this->data_url, ['multipart' => $form_body]);
        } else {
            $response = $this->http_client->request('PUT', $this->data_url, ['json' => $data]);
        }
        $response_data = json_decode($response->getBody(), true);
        $this->validateData($response_data);
        return $response_data;
    }

    private function validateData($response_data)
    {
        if (array_key_exists('_error', $response_data)) {
            switch ($response_data['_message']) {
                case 'Secret Key Error':
                    throw new AuthException("Secret Key Error!");
                    break;
                case 'Process not found.':
                    throw new AuthException("Secret Key Error!");
                    break;
                case 'Not Found':
                    throw new InvalidTokenException("Invalid Token!");
                    break;
                default:
            }
        }
    }

    private function hasFiles($data)
    {
        foreach ($data as $key => $value) {
            if (is_resource($value)) {
                if (get_resource_type($value) == 'file' ||
                    get_resource_type($value) == 'stream') {
                    return true;
                }
            }
        }

        return false;
    }

    private function isFile($value)
    {
        if (is_resource($value)) {
            return (get_resource_type($value) == 'file' ||
                get_resource_type($value) == 'stream');
        }
        return false;
    }

    private function getMultipartArray($data)
    {
        $multiartArray = [];
        foreach ($data as $key => $value) {
            if ($this->isFile($value)) {
                $multiartArray[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
                unset($data[$key]);
            }
        }
        $multiartArray[] = [
            'name' => 'inputdata',
            'contents' => json_encode($data),
        ];
        return $multiartArray;
    }

    private function validateAndParseArgs($args)
    {
        foreach ($args as $key => $value) {
            switch ($key) {
                case 'ids':
                    $args[$key] = implode(',', $value);
                    break;
                case 'order_by':
                    if (!in_array($value, ['asc', 'desc'])) {
                        throw new \InvalidArgumentException("order_by must be 'asc' or 'desc'");
                    }
                    $args[$key] = strtoupper($value);
                    break;
                case 'limit':
                    if (!is_int($value)) {
                        throw new \InvalidArgumentException('limit must be a non-negative integer');
                    }
                    break;
                case 'start':
                    if (!is_int($value)) {
                        throw new \InvalidArgumentException('start must be a non-negative integer');
                    }
                    $args['from'] = $value;
                    unset($args[$key]);
                    break;
                case 'to':
                    if (!is_int($value)) {
                        throw new \InvalidArgumentException('to must be a non-negative integer');
                    }
                    break;
            }
        }

        return $args;
    }
}
