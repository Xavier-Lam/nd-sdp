<?php
namespace ND\SDP\Clients\UC;

use ND\SDP\SdpApp;
use ND\SDP\SdpOrg;
use ND\SDP\UC\Session;
use ND\SDP\UC\User;
use ND\SDP\Utils;

class AccountClient extends BaseUCClient
{
    /**
     * 通过手机注册个人帐户
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Fperson_accounts.2Factions.2Fregister_by_mobile_.E9.80.9A.E8.BF.87.E6.89.8B.E6.9C.BA.E6.B3.A8.E5.86.8C.E4.B8.AA.E4.BA.BA.E5.B8.90.E6.88.B7
     */
    public function registerByMobile(Session $session, $tel, $smsCode, $password = '', $nickname = '', $countryCode = '+86', $autoLogin = true)
    {
        $sessionId = $session->getSessionId();
        $sessionKey = $session->getSessionKey();
        $password1 = Utils::saltedMD5($password); // 第一层加密：加密算法由uc_sdk提供
        $password2 = Utils::encryptDes($password1, $sessionKey); // 第二层加密：需要用2.1.1创建会话时返回的会话密钥(session_key)进行DES对称加密
        $data = $this->sendWithApp(
            $session->app,
            '/v1.1/person_accounts/actions/register_by_mobile',
            'POST',
            [
                'session_id' => $sessionId,
                'country_code' => $countryCode,
                'mobile' => Utils::encryptDes($tel, $sessionKey),
                'sms_code' => $smsCode,
                'nick_name' => $nickname,
                'auto_login' => $autoLogin,
                'password' => $password2
            ]
        )->json();

        return User::getByData($session->app, $data, $session);
    }

    /**
     * 获取个人帐户信息
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D.2Fperson_accounts.2F.7Baccount_id.7D_.E8.8E.B7.E5.8F.96.E4.B8.AA.E4.BA.BA.E5.B8.90.E6.88.B7.E4.BF.A1.E6.81.AF
     */
    public function getUserInfo(SdpApp $app, $accountId)
    {
        return $this->sendWithAppAuth($app, "/v1.1/person_accounts/$accountId")->json();
    }

    /**
     * 加入组织
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Fperson_accounts.2F.7Baccount_id.7D.2Factions.2Fjoin_org_.E4.B8.AA.E4.BA.BA.E5.B8.90.E6.88.B7.E5.8A.A0.E5.85.A5.E7.BB.84.E7.BB.87
     */
    public function joinOrg($accountId, Session $session, SdpOrg $org, $nodeId = null, $authInfo = null)
    {
        $data = [
            'org_id' => $org->sdpOrgId,
            'session_id' => $session->getSessionId()
        ];
        if($nodeId) {
            $data['node_id'] = $nodeId;
        }
        if($authInfo) {
            $data['auth_info'] = $authInfo;
        }
        $url = "/v1.1/person_accounts/$accountId/actions/join_org";
        $data = $this->sendWithAppAuth($session->app, $url, 'POST', $data)->json();

        return User::getByData($data, $session->app, $session);
    }

    /**
     * 获取个人帐户加入组织申请列表
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D.2Fperson_accounts.2F.7Baccount_id.7D.2Fapplications.3Fapply_status.3D.7Bapply_status.7D.26begin_verify_time.3D.7Bbegin_verify_time.7D.26end_verify_time.3D.7Bend_verify_time.7D.26is_filter.3D.7Bis_filter.7D.26org_id.3D.7Borg_id.7D.26.24offset.3D.7Boffset.7D.26.24limit.3D.7Blimit.7D_.E8.8E.B7.E5.8F.96.E4.B8.AA.E4.BA.BA.E5.B8.90.E6.88.B7.E5.8A.A0.E5.85.A5.E7.BB.84.E7.BB.87.E7.94.B3.E8.AF.B7.E5.88.97.E8.A1.A8
     */
    public function orgApplies(User $user, SdpApp $app = null)
    {
        return $this->sendWithAppAuth($app?: $user->app, "/v1.1/person_accounts/{$user->accountId}/applications")
            ->json();
    }

    /**
     * 获取个人帐户关联组织帐户信息接口
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D_.2Fperson_accounts.2F.7Baccount_id.7D.2Fbinded.2Fusers.3Fis_filter.3D.7Bis_filter.7D.26.24offset.3D.7Boffset.7D.26.24limit.3D.7Blimit.7D.26org_id.3D.7Borg_id.7D.26inst_id.3D.7Binst_id.7D_.E8.8E.B7.E5.8F.96.E4.B8.AA.E4.BA.BA.E5.B8.90.E6.88.B7.E5.85.B3.E8.81.94.E7.BB.84.E7.BB.87.E5.B8.90.E6.88.B7.E4.BF.A1.E6.81.AF.E6.8E.A5.E5.8F.A3
     */
    public function orgBound(User $user, $query = [], SdpApp $app = null)
    {
        return $this->sendWithAppAuth($app?: $user->app, "/v1.1/person_accounts/{$user->accountId}/binded/users", 'GET', $query)
            ->json()['items'];
    }
}