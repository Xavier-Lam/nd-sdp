<?php
namespace ND\SDP\Clients\UC\ThirdAccount;

use ND\SDP\Clients\UC\BaseUCClient;

/**
 * 第三方账户
 * https://wiki.doc.101.com/index.php?title=新第三方服务端
 * 
 * @property MiniProgram $miniProgram 小程序
 * @property WeChatPub $wechatPub 微信公众号
 */
class ThirdAccount extends BaseUCClient
{
    const ENV_PROD = 'https://third-auth-proxy.101.com';
    const ENV_BETA = 'https://third-auth-proxy.beta.101.com';
    const ENV_AWSCA = 'https://third-auth-proxy.awsca.101.com';
    const ENV_HK = 'https://third-auth-proxy.hk.101.com';

    protected $clients = [
        'miniProgram' => MiniProgram::class,
        'wechatPub' => WeChatPub::class
    ];
}