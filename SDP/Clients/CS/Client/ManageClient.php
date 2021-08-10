<?php
namespace ND\SDP\Clients\CS\Client;

use ND\SDP\CS\Policy;
use ND\SDP\CS\Utils;

/**
 * 文件管理
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E5.88.A0.E9.99.A4.E7.9B.AE.E5.BD.95.E9.A1.B9
 */
class ManageClient extends BaseCSClient
{
    /**
     * 删除单个项目
     * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E5.8D.95.E4.B8.AA.E5.88.A0.E9.99.A4.E7.9B.AE.E5.BD.95.E9.A1.B9_.5BDELETE.5D_.2Fv0.1.2Fdentries.2F.7BdentryId.7D.3Fsession.3D.7Bsession.7D.5B.26token.3D.7Btoken.7D.26policy.3D.7Bpolicy.7D.26date.3D.7Bdate.7D.5D
     */
    public function delete(Policy $policy)
    {
        $policy->setServiceName($this->getServiceName());
        if($policy->path) {
            $path = Utils::quotePath($policy->path);
            $url = "/v0.1/static{$path}";
        } else {
            $url = "/v0.1/dentries/{$policy->dentryId}";
        }
        $preparedRequest = $this->createPrepareRequestWithPolicy($url, $policy, [], 'DELETE');
        return $this->send($preparedRequest);
    }

    /**
     * 批量移除项目
     * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E6.89.B9.E9.87.8F.E5.88.A0.E9.99.A4.E7.9B.AE.E5.BD.95.E9.A1.B9_.5BPATCH.5D_.2Fv0.1.2Fdentries.2Factions.2Fdelete.3Fsession.3D.7Bsession.7D.5B.26token.3D.7Btoken.7D.26policy.3D.7Bpolicy.7D.26date.3D.7Bdate.7D.5D
     */
    public function batchDelete(Policy $policy, $items)
    {
        if($policy->dentryId) {
            $data = [
                'parent_id' => $policy->dentryId,
                'dentry_ids' => $items
            ];
            $params = [];
        } else {
            $policy->path = Utils::fixPathRoot($policy->path, $this->getServiceName());
            $data = [
                'parent_path' => $policy->path,
                'paths' => $items
            ];
            $params = ['fromPath' => 'true'];
        }
        $url = '/v0.1/dentries/actions/delete';
        $preparedRequest = $this->createPrepareRequestWithPolicy(
            $url, $policy, $data, 'PATCH', $params);
        return $this->send($preparedRequest);
    }
}