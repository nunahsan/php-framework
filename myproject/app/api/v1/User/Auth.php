<?php

namespace Api\V1\User;

// use Exception;
use Helpers\Mysql as DB;
use Helpers\Mongo as MONGO;

class Auth
{
    public function __construct()
    {
    }

    public function login()
    {
        $db = DB::getInstance();

        $data = $db->query("select * from users where id=?", [2]);

        return response(true, 'OK LA', $data);
    }

    public function listUser()
    {
        $db = DB::getInstance();
        $conn = $db->getConnection();

        $data = [
            'insertedId' => [],
            'affectedRow' => 0,
            'list' => []
        ];
        try {
            $conn->beginTransaction();

            //do insert
            $insertedId = $db->insert("insert into users (name, age, username, password) values(?,?,?,?)", [
                'alibaba', '12', 'alibaba', '12345'
            ]);
            $data['insertedId'][] = $insertedId;

            //do update
            $affectedRow = $db->update("update users set name='haha' where id=?", [15]);
            $data['affectedRow'] = $affectedRow;

            //do query
            $res = $db->query("select * from users where id>?", [0]);
            $data['list'] = $res;

            $conn->commit();
        } catch (\PDOException $e) {
            $conn->rollBack();
            response(false, 'Transaction failed : ' . $e->getMessage());
        }

        return response(true, 'OK LA', $data);
    }

    public function test()
    {
        try {
            $mongo = MONGO::getInstance();
            $conn = $mongo->getConnection();

            //sample : select all
            //$data = $mongo->find('api_clients');

            //sample : select with filter
            // $data = $mongo->find('api_clients', [
            //     '_id' => $mongo->ObjectId('64c0ccea801a1954e957f275')
            // ]);

            //sample : select with filter and additional options
            $data = $mongo->find(
                'api_clients',
                [
                    '_id' => $mongo->ObjectId('64c0ccea801a1954e957f275')
                ],
                [
                    'projection' => [
                        '_id' => 0, //exclude
                        'client_identifier' => 1, //include
                        'api_key' => 1
                    ]
                ]
            );
        } catch (\Exception $e) {
            response(false, $e->getMessage());
        }

        response(true, '', $data);
    }
}
