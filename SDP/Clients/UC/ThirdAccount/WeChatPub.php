<?php
namespace ND\SDP\Clients\UC\ThirdAccount;

use ND\SDP\Clients\UC\BaseUCClient;
use ND\SDP\SdpApp;
use ND\SDP\UC\Session;
use ND\SDP\UC\User;

/**
 * 第三方账户 - 微信公众号
 * https://wiki.doc.101.com/index.php?title=新第三方服务端#.E5.BE.AE.E4.BF.A1.E5.85.AC.E4.BC.97.E5.8F.B7
 */
class WeChatPub extends BaseUCClient
{
    /**
     * WEB/H5端第三方帐号登录
     * https://wiki.doc.101.com/index.php?title=新第三方服务端#.5BPOST.5D.2Fwxpub.2Fweb.2Ftokens.2Factions.2Fcreate_by_third_account_WEB.2FH5.E7.AB.AF.E7.AC.AC.E4.B8.89.E6.96.B9.E5.B8.90.E5.8F.B7.E7.99.BB.E5.BD.95
     * 
     * @param $appId 微信appid
     * @param $code 微信重定向code
     * @return User
     */
    public function webCreate(Session $session, $appId, $code, $scope='', $autoRegister = true)
    {
        $url = '/v1.1/wxpub/web/tokens/actions/create_by_third_account';
        $redirectParams = [
            'app_id' => $appId,
            'code' => $code
        ];
        $scope && $redirectParams['scope'] = $scope;
        $data = $this->sendWithApp($session->app, $url, 'POST', [
            'redirect_params' => http_build_query($redirectParams),
            'session_id' => $session->id,
            'auto_register' => $autoRegister
        ])->json();

        $data = $session->decryptUserData($data);

        return User::getByData($data, $session->app);
    }

    /**
     * WEB/H5端绑定第三方帐号
     * https://wiki.doc.101.com/index.php?title=新第三方服务端#.5BPOST.5D.2Fwxpub.2Fweb.2Fperson_accounts.2F.7Baccount_id.7D.2Fthird_accounts.2Factions.2Fbind_WEB.2FH5.E7.AB.AF.E7.BB.91.E5.AE.9A.E7.AC.AC.E4.B8.89.E6.96.B9.E5.B8.90.E5.8F.B7
     * 
     * @param $appId 微信appid
     * @param $code 微信重定向code
     */
    public function webBind($accountId, Session $session = null, SdpApp $app = null, $appId = null, $code = null)
    {
        if(!$session && (!$app || !$appId || !$code)) {
            throw new \InvalidArgumentException('必须传入session或appId+code');
        }

        $app = $app?: $session->app;
        $url = "/v1.1/wxpub/web/person_accounts/$accountId/third_accounts/actions/bind";
        $data = [];
        if($session) {
            $data['session_id'] = $session->id;
        }
        if($app && $appId && $code) {
            $data['redirect_params'] = http_build_query([
                'app_id' => $appId,
                'code' => $code
            ]);
        }
        $this->sendWithAppAuth($app, $url, 'POST', $data);
    }
}