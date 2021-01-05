<?php
namespace ND\SDP\Client;

use Shisa\HTTPClient\Auth\AbstractAuth;
use Shisa\HTTPClient\Clients\AuthMixin;
use Shisa\HTTPClient\Clients\IAuthClient;

class AuthClient extends BaseClient implements IAuthClient
{
    use AuthMixin;

    public function setAuth(AbstractAuth $auth = null)
    {
        $this->auth = $auth;
        parent::setAuth($auth);
        $auth && !$auth->getClient() && $auth->setClient($this->getBaseClient());
    }
}