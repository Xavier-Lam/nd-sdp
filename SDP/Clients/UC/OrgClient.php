<?php
namespace ND\SDP\Clients\UC;

use ND\SDP\SdpApp;
use ND\SDP\SdpOrg;

class OrgClient extends BaseUCClient
{
    /**
     * 获取组织信息
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D_.2Forganizations.2F.7Borg_id.7D.3Fwith_relation.3D.7Bwith_relation.7D.26with_tag.3D.7Btrue.7Cfalse.7D_.E8.8E.B7.E5.8F.96.E7.BB.84.E7.BB.87.E4.BF.A1.E6.81.AF
     */
    public function getInfo(SdpOrg $org, SdpApp $app = null, $withRelation = false, $withTag = false)
    {
        $app = $app?: $org->sdpApp;
        if($app) {
            $headers = ['sdp-app-id' => $app->sdpAppId];
        } else {
            $headers = [];
        }
        return $this->sendWithAuth(
            "/v1.1/organizations/$org->sdpOrgId",
            [
                'with_relation' => $app && $withRelation,
                'with_tag' => $app && $withTag
            ],
            'GET',
            [],
            $headers
        )->json();
    }

    /**
     * 根据组织代码查询组织节点信息
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D_.2Forganizations.2Factions.2Fquery.3Fwith_ext.3D.7Bfalse.7Ctrue.7D_.E6.A0.B9.E6.8D.AE.E7.BB.84.E7.BB.87.E4.BB.A3.E7.A0.81.E6.9F.A5.E8.AF.A2.E7.BB.84.E7.BB.87.E8.8A.82.E7.82.B9.E4.BF.A1.E6.81.AF
     */
    public function queryOrg($type, $items)
    {
        return $this->sendWithAuth(
            "/v1.1/organizations/actions/query",
            [
                'type' => $type,
                'items' => $items
            ],
            'POST'
        )->json();
    }

    /**
     * 根据组织代码查询组织节点公开信息
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D_.2Fpublic.2Forganizations.2Factions.2Fquery_.E6.A0.B9.E6.8D.AE.E7.BB.84.E7.BB.87.E4.BB.A3.E7.A0.81.E6.9F.A5.E8.AF.A2.E7.BB.84.E7.BB.87.E8.8A.82.E7.82.B9.E5.85.AC.E5.BC.80.E4.BF.A1.E6.81.AF
     */
    public function queryOrgPublic($type, $items)
    {
        return $this->sendWithAuth(
            "/v1.1/public/organizations/actions/query",
            [
                'type' => $type,
                'items' => $items
            ],
            'POST'
        )->json();
    }
}