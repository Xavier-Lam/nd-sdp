<?php
namespace ND\SDP\Clients\UC;

/**
 * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口
 *
 * @property AccountClient $account
 * @property OrgClient $org
 * @property SessionClient $session
 * @property ThirdAccount\ThirdAccount $thirdAccount
 * @property TokenClient $token
 * @property UserClient $user
 */
class UCClient extends BaseUCClient
{
    const ENV_PROD = 'https://uc-gateway.101.com';
    const ENV_BETA = 'https://uc-gateway.beta.101.com';
    const ENV_DEBUG = 'http://uc-gateway.debug.web.nd';
    const ENV_DEV = 'http://uc-gateway.dev.web.nd';
    const ENV_AWSCA = 'https://uc-gateway.awsca.101.com';
    const ENV_HK = 'https://uc-gateway.hk.101.com';

    protected $clients = [
        'account' => AccountClient::class,
        'org' => OrgClient::class,
        'session' => SessionClient::class,
        'thirdAccount' => ThirdAccount\ThirdAccount::class,
        'token' => TokenClient::class,
        'user' => UserClient::class
    ];
}