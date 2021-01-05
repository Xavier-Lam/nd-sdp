<?php
namespace ND\SDP\Clients\Admin;

use ND\SDP\Client\AuthClient;

class OrgClient extends AuthClient
{
    /**
     * 新增组织
     * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口#.5BPOST.5D_.2Forganizations_.E6.96.B0.E5.A2.9E.E7.BB.84.E7.BB.87
     */
    public function create($orgName, $orgCode, $nodeType, $data = [])
    {
        $data['org_name'] = $orgName;
        $data['node_type'] = $nodeType;
        $data['org_code'] = $orgCode;
        return $this->sendWithAuth('/v1.1/organizations', $data, 'POST')
            ->json();
    }

    /**
     * 修改组织
     * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口#.5BPATCH.5D_.2Forganizations.2F.7Borg_id.7D_.E4.BF.AE.E6.94.B9.E7.BB.84.E7.BB.87
     */
    public function update($orgId, $update)
    {
        return $this->sendWithAuth("/v1.1/organizations/$orgId", $update, 'PATCH')
            ->json();
    }
}