<?php
namespace ND\SDP\Clients\UC\ThirdAccount;

use ND\SDP\Clients\UC\BaseUCClient;
use ND\SDP\UC\Session;
use ND\SDP\UC\User;

/**
 * 第三方账户 - 微信小程序
 * https://wiki.doc.101.com/index.php?title=新第三方服务端#.E5.BE.AE.E4.BF.A1.E5.B0.8F.E7.A8.8B.E5.BA.8F
 */
class MiniProgram extends BaseUCClient
{
    /**
     * web端第三方帐号登录
     * https://wiki.doc.101.com/index.php?title=新第三方服务端#.5BPOST.5D.2Fminiprogram.2Fweb.2Ftokens.2Factions.2Fcreate_by_third_account_web.E7.AB.AF.E7.AC.AC.E4.B8.89.E6.96.B9.E5.B8.90.E5.8F.B7.E7.99.BB.E5.BD.95
     * 
     * @param $thirdPlatAppId 第三方平台给应用分配的Id
     * @return User
     */
    public function webTokens(Session $session, $thirdPlatAppId, $code, $autoRegister = true, $encryptedData='', $iv='')
    {
        $url = '/v1.1/miniprogram/web/tokens/actions/create_by_third_account';
        $data = $this->sendWithApp($session->app, $url, 'POST', [
            'code' => $code,
            'session_id' => $session->id,
            'third_plat_app_id' => $thirdPlatAppId,
            'encrypted_data' => $encryptedData,
            'iv' => $iv,
            'auto_register' => $autoRegister
        ])->json();

        $data = $session->decryptUserData($data);

        $data['third'] = [
            'openid' => $data['open_id']
        ];
        return User::getByData($data, $session->app);
    }

    /**
     * web端第三方帐号信息更新
     * https://wiki.doc.101.com/index.php?title=新第三方服务端#.5BPUT.5D.2Fminiprogram.2Fweb.2Factions.2Faccount_info_web.E7.AB.AF.E7.AC.AC.E4.B8.89.E6.96.B9.E5.B8.90.E5.8F.B7.E4.BF.A1.E6.81.AF.E6.9B.B4.E6.96.B0
     * 
     * @param $thirdPlatAppId 第三方平台给应用分配的Id
     */
    public function webBind(Session $session, $thirdPlatAppId, $code, $encryptedData='', $iv='')
    {
        $url = "/v1.1/miniprogram/web/actions/account_info";
        $this->sendWithAppAuth($session->app, $url, 'PUT', [
            'code' => $code,
            'session_id' => $session->id,
            'third_plat_app_id' => $thirdPlatAppId,
            'encrypted_data' => $encryptedData,
            'iv' => $iv
        ]);
    }
}