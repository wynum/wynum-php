<?php namespace Wynum\Rest;

class Schema
{
    public $key;
    public $type;

    public function __construct($schemaObject)
    {
        $this->key = $schemaObject['Property'];
        $this->type = $schemaObject['Type'];
    }
}
