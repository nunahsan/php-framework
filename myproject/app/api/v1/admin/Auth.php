<?php
//admin
namespace Api\V1\Admin;

use Helpers\Mysql as DB;

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

            sleep(5);

            $conn->commit();
        } catch (\PDOException $e) {
            $conn->rollBack();
            response(false, 'Transaction failed : ' . $e->getMessage());
        }

        return response(true, 'OK LA', $data);
    }
}
