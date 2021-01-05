<?php
namespace ND\SDP\Clients\Admin;

use ND\SDP\Client\AuthClient;

/**
 * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口
 *
 * @property AppClient $app
 * @property InstClient $inst
 * @property OrgClient $org
 * @property ThirdAppClient $thirdApp
 */
class AdminClient extends AuthClient
{
    const ENV_PROD = 'https://admin-gateway.101.com';
    const ENV_BETA = 'https://admin-gateway.beta.101.com';
    const ENV_DEBUG = 'http://admin-gateway.debug.web.nd';
    const ENV_DEV = 'http://admin-gateway.dev.web.nd';
    const ENV_AWSCA = 'https://admin-gateway.awsca.101.com';
    const ENV_HK = 'https://admin-gateway.hk.101.com';

    protected $clients = [
        'app' => AppClient::class,
        'inst' => InstClient::class,
        'org' => OrgClient::class,
        'thirdApp' => ThirdAppClient::class
    ];
}