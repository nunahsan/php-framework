<?php

class Rules
{
    const String = 'string';
    const Integer = 'integer';
    const Double = 'double';
    const Array = 'array';
    const Required = true;
    const Optional = false;
};

const ROUTES = [
    '/v1/user/login' => [
        'namespace' => 'Api\V1\User',
        'class' => 'Auth',
        'action' => 'login',
        'params' => [
            'username' => [
                'type' => Rules::String,
                'required' => Rules::Required,
                'minLength' => 3,
                'maxLength' => 5,
                'exactLength' => 4
            ]
        ]
    ],
    '/v1/user/list' => [
        'namespace' => 'Api\V1\User',
        'class' => 'Auth',
        'action' => 'listUser'
    ],
    '/v2/user/login' => [
        'namespace' => 'V2\User',
        'class' => 'Auth',
        'action' => 'login'
    ],
    '/v1/user/test' => [
        'namespace' => 'Api\V1\User',
        'class' => 'Auth',
        'action' => 'test'
    ],
];

