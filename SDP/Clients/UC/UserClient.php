<?php
namespace ND\SDP\Clients\UC;

use ND\SDP\SdpApp;

class UserClient extends BaseUCClient
{
    /**
     * 获取成员信息
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D_.2Fusers.2F.7Buser_id.7D.3Fwith_ext.3D.7Bfalse.7Ctrue.7D.26with_tag.3D.7Bfalse.7Ctrue.7D_.E8.8E.B7.E5.8F.96.E6.88.90.E5.91.98.E4.BF.A1.E6.81.AF
     */
    public function getUserInfo(SdpApp $app, $userId)
    {
        return $this->sendWithAppAuth(
            $app,
            "/v1.1/users/$userId"
        )->json();
    }

    /**
     * 获取组织帐户绑定的个人帐户
     * https://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D_.2Fusers.2F.7Buser_id.7D.2Fperson_account_.E8.8E.B7.E5.8F.96.E7.BB.84.E7.BB.87.E5.B8.90.E6.88.B7.E7.BB.91.E5.AE.9A.E7.9A.84.E4.B8.AA.E4.BA.BA.E5.B8.90.E6.88.B7
     */
    public function getPersonalAccount($userId)
    {
        return $this->sendWithAuth("/v1.1/users/$userId/person_account")->json();
    }

    /**
     * 获取组织成员公开信息
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D_.2Fpublic.2Fusers.2F.7Buser_id.7D_.E8.8E.B7.E5.8F.96.E7.BB.84.E7.BB.87.E6.88.90.E5.91.98.E5.85.AC.E5.BC.80.E4.BF.A1.E6.81.AF
     */
    public function getPublicUserInfo(SdpApp $app, $userId)
    {
        return $this->sendWithAppAuth(
            $app,
            "/v1.1/public/users/$userId"
        )->json();
    }
}