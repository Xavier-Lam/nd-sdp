<?php
namespace ND\SDP;

use ND\SDP\Auth\BTSAuth;

class BTSApp extends BTSAuth
{
    public $id;

    public $name;

    public $secret;

    public static function get($name, $secret, $id)
    {
        return new static($name, $secret, $id);
    }

    public function __construct($name, $secret, $id) {
        $this->id = $id;
        $this->name = $name;
        $this->secret = $secret;
    }

    public function auth()
    {
        $data = $this->getClient()->bts->token->get($this, true);
        $this->update($data);
    }
}