<?php

namespace Api\V2\User;

class Auth
{
    public function login()
    {
        $data = [
            'age' => 20,
            'name' => 'hasan'
        ];

        return response(true, 'hahaha', $data);
    }
}
