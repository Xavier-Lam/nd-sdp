<?php
namespace ND\SDP;

use ND\SDP\Client\AuthClient;
use ND\SDP\Envs;
use Shisa\HTTPClient\Auth\AbstractAuth;
use ND\SDP\Clients\CS as Clients;

/**
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E5.AE.A2.E6.88.B7.E7.AB.AF.E6.8E.A5.E5.8F.A3
 * 
 * @property Clients\Client\DownloadClient $download
 * @property Clients\Client\UploadClient $upload
 * @property Clients\Server\SessionClient $session
 * @property Clients\Client\ManageClient $manage
 */
class CS extends AuthClient
{
    protected $env = Envs::PROD;

    const ENV_PROD = 'https://cs.101.com';
    const ENV_BETA = 'https://betacs.101.com';

    protected $clients = [
        'download' => Clients\Client\DownloadClient::class,
        'manage' => Clients\Client\ManageClient::class,
        'session' => Clients\Server\SessionClient::class,
        'upload' => Clients\Client\UploadClient::class
    ];

    public function __construct(AbstractAuth $auth = null)
    {
        $this->setBaseClient($this);
        parent::__construct($auth);
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