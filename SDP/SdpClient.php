<?php
namespace ND\SDP;

use ND\SDP\Client\AuthClient;
use ND\SDP\UC\User;
use Shisa\HTTPClient\Auth\AbstractAuth;

/**
 * SdpClient
 *
 * @property Clients\Admin\AdminClient $admin
 * @property Clients\BTS\BTSClient $bts
 * @property Clients\Censor2\Censor2Client $censor2
 * @property Clients\UC\UCClient $uc
 * @method Clients\Wallet\WalletClient wallet(\ND\SDP\SdpTenant $tenant)
 */
class SdpClient extends AuthClient
{
    protected $env = Envs::PROD;

    protected $clients = [
        'admin' => Clients\Admin\AdminClient::class,
        'bts' => Clients\BTS\BTSClient::class,
        'censor2' => Clients\Censor2\Censor2Client::class,
        'uc' => Clients\UC\UCClient::class,
        'wallet' => Clients\Wallet\WalletClient::class,
    ];

    public static function create(AbstractAuth $auth = null)
    {
        return new static($auth);
    }

    public function __construct(AbstractAuth $auth = null)
    {
        $this->setBaseClient($this);
        parent::__construct($auth);
    }

    public function setAuth(AbstractAuth $auth = null)
    {
        parent::setAuth($auth);
        if($auth && $auth instanceof User && $auth->app && $auth->app->env) {
            $this->setEnv($auth->app->env);
        }
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function setEnv($env)
    {
        $this->env = $env;
    }
}