<?php

namespace Helpers;

//reference : https://www.mongodb.com/docs/php-library/current/reference/class/MongoDBClient

use MongoDB\Client as Client;
use MongoDB\BSON\ObjectId as BsonObjectId;

class Mongo
{

    private static $instance = null;
    private $conn;
    private $dbName;

    public function __construct()
    {
        try {
            $this->conn = new Client(
                getenv('MONGO_CONN'),
                [
                    'ssl' => false,
                    'replicaSet' => 'replicasetMain',
                    'authSource' => 'admin',
                    'readPreference' => 'secondaryPreferred'
                ]
            );

            $this->dbName = getenv('MONGO_DB');
        } catch (\Exception $e) {
            response(false, 'mongo connection error: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Mongo();
        }

        return self::$instance;
    }

    public function find($collection_name, $filter = [], $options = [])
    {
        $collection = $this->conn->selectCollection($this->dbName, $collection_name);
        $data = $collection->find($filter, $options)->toArray();
        return $data;
    }

    public function ObjectId($id)
    {
        return new BsonObjectId($id);
    }
}
