<?php

require_once __DIR__ . '/../vendor/autoload.php';

// /**
// *  Corresponding Class to test YourClass class
// *
// *  For each class in your library, there should be a corresponding Unit-Test for it
// *  Unit-Tests should be as much as possible independent from other test going on.
// *
// *  @author yourname
// */
// class ClientTest extends PHPUnit_Framework_TestCase{
//   /**
//   * Just check if the YourClass has no syntax error
//   *
//   * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
//   * any typo before you even use this library in a real project.
//   *
//   */
//   public function testMethod1(){
//     $client = new Wynum\Rest\Client("a742bc685f072fe624f61ee2119d66982x", "8979947");
//     $client->getData();
//     // $this->assertTrue($var->method1("hey") == 'Hello World');
//     // unset($var);
//   }

// }

$client = new Wynum\Rest\Client("a742bc685f072fe624f61ee2119d66982x", "8979947");
$data = ['name' => 'groot',
    'height' => 5,
    'weight' => 42,
    'Details_hobbies' => ['painting'],
    'Details_required' => 'yes',
    'id' => '2'];
// $res = $client->updateData($data);
$res = $client->getSchema();
foreach ($res as $schema) {
    echo "{$schema->key}, {$schema->type}\n";
}
// $res = $client->postData($data);
// $args = [
//     // 'limit' => 4,
//     'order_by' => 'asc',
//     'ids' => ['1', '2']
// ];
// $res = $client->getData($args);
// // echo $client->identifier;
// print_r($res);

// $f = fopen(__DIR__ . '/../.gitignore', 'r');

// $client =  new Wynum\Rest\Client("a742bc685f072fe624f61ee2119d66982x", "6138287");
// $data = ["id" => "2", "file" => $f];
// $r = $client->postData($data);
// $r = $client->updateData($data);
// print_r($r);