<?php
require_once __DIR__ . '/../vendor/autoload.php';

use \PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private $client;
    private $data;
    private $wrong_key_client;
    private $invalid_token_client;

    public function __construct()
    {
        $this->client = new Wynum\Rest\Client(
            "a742bc685f072fe624f61ee2119d66982x",
            "8979947"
        );
        $this->data = ['name' => 'groot',
            'height' => 5,
            'weight' => 42,
            'Details_hobbies' => ['painting'],
            'Details_required' => 'yes',
            'id' => '2'];
        $this->wrong_key_client = new Wynum\Rest\Client(
            "a742bc685f072fe624f61ee2119d66982",
            "8979947"
        );
        $this->invalid_token_client = new Wynum\Rest\Client(
            "a742bc685f072fe624f61ee2119d66982x",
            "897994"
        );
    }

    public function testPostData()
    {
        $res = $this->client->postData($this->data);
        $this->assertArrayNotHasKey("error", $res);

        $this->expectException(Wynum\Rest\AuthException::class);
        $this->wrong_key_client->postData($this->data);

        $this->expectException(Wynum\Rest\InvalidTokenException::class);
        $this->invalid_token_client->postData($this->data);
    }

    public function testPostDataWithFile()
    {
        $client = new Wynum\Rest\Client("a742bc685f072fe624f61ee2119d66982x", "6138287");
        $data = ["id" => "2", "file" => fopen(__FILE__, 'r')];
        $res = $client->postData($data);
        $this->assertArrayNotHasKey("error", $res);
    }

    public function testUpdateData()
    {
        $res = $this->client->updateData($this->data);
        $this->assertArrayNotHasKey("error", $res);

        $this->expectException(Wynum\Rest\AuthException::class);
        $this->wrong_key_client->updateData($this->data);

        $this->expectException(Wynum\Rest\InvalidTokenException::class);
        $this->invalid_token_client->updateData($this->data);
    }

    public function testUpdateDataWithFile()
    {
        $client = new Wynum\Rest\Client("a742bc685f072fe624f61ee2119d66982x", "6138287");
        $data = ["id" => "2", "file" => fopen(__FILE__, 'r')];
        $res = $client->updateData($data);
        $this->assertArrayNotHasKey("error", $res);
    }

    public function testGetData()
    {
        $res = $this->client->getData();
        $this->assertArrayNotHasKey("error", $res);

        $this->expectException(Wynum\Rest\AuthException::class);
        $this->wrong_key_client->getData();

        $this->expectException(Wynum\Rest\InvalidTokenException::class);
        $this->invalid_token_client->getData();
    }

    public function testGetSchema()
    {
        $res = $this->client->getSchema();
        $this->assertArrayNotHasKey("error", $res);

        $this->expectException(Wynum\Rest\AuthException::class);
        $this->wrong_key_client->getSchema();

        $this->expectException(Wynum\Rest\InvalidTokenException::class);
        $this->invalid_token_client->getSchema();
    }

}
