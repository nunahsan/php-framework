<?php

namespace Api\V1\User;

class Wallet
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function deposit()
    {
        return response(true, 'u deposit', []);
    }
}
