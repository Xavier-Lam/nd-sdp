<?php
namespace ND\SDP\Clients\BTS;

use ND\SDP\Client\AuthClient;

/**
 * BTSAPI
 * http://wiki.doc.101.com/index.php?title=BTSAPI
 *
 * @property TokenClient $token
 */
class BTSClient extends AuthClient
{
    protected $clients = [
        'token' => TokenClient::class
    ];

    const ENV_PROD = 'https://ucbts.101.com';
    const ENV_BETA = 'https://betabts.101.com';
}