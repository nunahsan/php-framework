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
        ],
        '/v1/wallet/deposit' => [
            'module' => 'module2'
        ]
    ];
} else if (getenv('SERVERNAME') == 'module1') {
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
} else {
    //let say this is module2
    $routes = [
        '/v1/wallet/deposit' => [
            'namespace' => 'Api\V1\User',
            'class' => 'Wallet',
            'action' => 'deposit',
            'params' => [
                'amount' => [
                    'type' => Rules::Double,
                    'required' => Rules::Required
                ]
            ]
        ],
    ];
}
define('ROUTES', $routes);
