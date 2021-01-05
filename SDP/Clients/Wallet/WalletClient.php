<?php
namespace ND\SDP\Clients\Wallet;

use ND\SDP\Client\TenantClient;

/**
 * 钱包服务端
 * http://wiki.doc.101.com/index.php?title=养成币服务端
 *
 * @property PointClient $point
 */
class WalletClient extends TenantClient
{
    const ENV_PROD = 'https://zhifu.101.com';
    const ENV_BETA = 'https://pbl4wallet.beta.101.com';
    const ENV_QA = 'https://pbl4wallet.qa.101.com';
    const ENV_DEBUG = 'http://pbl4wallet.debug.web.nd';
    const ENV_DEV = 'http://pbl4wallet.dev.web.nd';
    const ENV_AWS = 'https://zhifu-aws.101.com';
    const ENV_AWSCA = 'https://zhifu-awsca.101.com';
    const ENV_HK = 'https://zhifu-hk.101.com';

    protected $clients = [
        'point' => PointClient::class
    ];
}