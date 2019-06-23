# Getting started
Very easy to use. Create a ```Client``` and you're ready to go.
## API Credentials
The ```Client``` needs Wynum credentials.You can either pass these directly to the constructor.
```php
<?php

$secret = "your_secret_key";
$token = "project_token";
$client = new Wynum\Rest\Client(secret, token);
```


## Get schema
call ```getSchema``` on ```Client``` to get the keys and types for the data. This will return an ```array``` of ```Wynum\Rest\Schema``` objects.  ```Schema->key``` will return the key and ```Schema->type``` will return the Wynum type. Following is the mapping from Wynum type to python type.

| Wynum type            | Python type                  |
| --------------------- | ---------------------------- |
| Text                  | ```string```                 |
| Date                  | ```string``` (dd/mm/yyyy)    |
| Number                | ```integer``` or ```float``` |
| Choice (Or)           | ```integer``` or ```float``` |
| Multiple Choice (And) | ```array``` of ```string```  |
| Time                  | ```string``` (hh:mm)         |
| File                  | ```File resource```          |     |

```php
<?php
$schemas = $client->getSchema();
foreach ($schemas as $schema) {
    echo "{$schema->key}, {$schema->type}\n";
}
```

## Post data
the ```postData``` method accepts a single parameter data which is an ```associative array``` containing the post key=>value pairs. Every data ```associative array``` should contain the 'identifier'. You can get identifier key if you have called ```getSchema```. You can retrieve it using ```$client->identifier```.

```php
<?php

$client.getSchema();
$identifer_key = client->identifier;
$data = ['key1'=>val1, 'key2'=>val2, identifer_key=>'id_string'];
$res = client->postData($data);
```
If the call is successful it returns the ```associative array``` containing the created data instance. If there is some error the ```associative array``` will contain ```_error``` and ```_message``` keys.  You should check this to check for errors.

## Get data
Call ```getData``` to get the data. This will return an ```array``` of ```associative array``` . ```getData``` accepts an associative array of parameters. The parameters are listed below 
- ```limit```: ```integer```
    <br>Number of records to return.
- ```order_by```: ```string```
    <br> Sorting order which can either be 'asc' or desc'
- ```ids```: ```array``` of ```string```
    <br> The list of ids to retrieve
- ```start```: ```integer```
    <br> Record number to start from
- ```to```: ```integer```
    <br> Record number to end at

```start``` and `to` can be used for pagination.

If no arguments are provided it will return the list of all data records.

```php
<?php
$data = $client->getData();
```

## Updating data
The ```updateData``` method is same as that of ```postData``` method.
```php
<?php

$client.getSchema();
$identifer_key = client->identifier;
$data = ['key1'=>val1, 'key2'=>val2, identifer_key=>'id_string'];
$res = client->updateData($data);
```