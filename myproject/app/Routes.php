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



if (getenv('SERVERNAME') == 'routing') {
    //let say this is module routing
    $routes = [
        '/v1/user/login' => [
            'module' => 'module1'
        ]
    ];
} else {
    //let say this is module1
    $routes = [
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
    ];
}
define('ROUTES', $routes);
