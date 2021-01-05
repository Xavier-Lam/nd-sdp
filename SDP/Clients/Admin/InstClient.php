<?php
namespace ND\SDP\Clients\Admin;

use ND\SDP\Client\AuthClient;

class InstClient extends AuthClient
{
    /**
     * 机构下添加组织
     * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口#.5BPOST.5D_.2Finstitutions.2F.7Binst_id.7D.2Forganizations.2F.7Borg_id.7D_.E6.9C.BA.E6.9E.84.E4.B8.8B.E6.B7.BB.E5.8A.A0.E7.BB.84.E7.BB.87
     */
    public function addOrg($instId, $orgId, $data = [])
    {
        $data['parent_org_id'] = $data['parent_org_id']?: 0;
        return $this->sendWithAuth("/v1.1/institutions/$instId/organizations/$orgId", $data, 'POST')
            ->json();
    }
}