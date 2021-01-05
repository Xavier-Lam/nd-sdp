<?php
namespace ND\SDP\Clients\Censor2;

use ND\SDP\Client\AuthClient;

/**
 * https://wiki.doc.101.com/index.php?title=敏感词服务端API
 * 
 * @property AppClient $app
 * @property GlobalClient $global
 */
class Censor2Client extends AuthClient
{
    const ENV_PROD = 'https://censor2.sdp.101.com';
    const ENV_BETA = 'https://censor2.beta.101.com';
    const ENV_AWSCA = 'https://censor2.awsca.101.com';
    const ENV_HK = 'https://censor2.hk.101.com';

    protected $clients = [
        'app' => AppClient::class,
        'global' => GlobalClient::class
    ];
}