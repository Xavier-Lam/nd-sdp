<?php
namespace ND\SDP\Clients\CS\Client;

use ND\SDP\CS\Dentry;
use ND\SDP\CS\Policy;
use ND\SDP\CS\Utils;

/**
 * 文件下载
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E4.B8.8B.E8.BD.BD.E6.96.87.E4.BB.B6.5B.E7.BC.A9.E7.95.A5.E5.9B.BE.5D.5Brange.E4.B8.8B.E8.BD.BD.5D_.5BGET.5D_.2Fv0.1.2Fdownload.3FdentryId.3D.7BdentryId.7D.26path.3D.7Bpath.7D.5B.26size.3D.7Bsize.7D.5D.5B.26session.3D.7Bsession.7D.5B.26token.3D.7Btoken.7D.26policy.3D.7Bpolicy.7D.26expireAt.3D.7BexpireAt.7D.5D.5D.5B.26attachment.3Dtrue.5D.5B.26name.3D.7Bname.7D.5D.5B.26ext.3D.7Bext.7D.5D.5B.26profile.3Dremove.5D
 */
class DownloadClient extends BaseCSClient
{
    /**
     * 普通文件下载
     * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E6.99.AE.E9.80.9A.E4.B8.8B.E8.BD.BD.E6.96.87.E4.BB.B6
     * 
     * @param array $params 请求参数
     */
    public function download(Policy $policy, $auth = false, $params = [], $headers = [])
    {
        $request = $this->downloadRequest($policy, $auth? 600: null, $params, $headers);
        return $this->send($request);
    }

    /**
     * 获取普通文件下载url
     * @return string url
     */
    public function downloadUrl(Policy $policy, $expiresIn = null, $params = [])
    {
        return $this->downloadRequest($policy, $expiresIn, $params)->uri;
    }

    private function downloadRequest(Policy $policy, $expiresIn = null, $params = [], $headers = [])
    {
        $policy->setServiceName($this->getServiceName());
        if($policy->path) {
            $params['path'] = $policy->path;
        } else {
            $params['dentryId'] = $policy->dentryId;
        }

        if($expiresIn) {
            $params['expireAt'] = time() + $expiresIn;
        }

        $url = '/v0.1/download';
        if($params['expireAt']) {
            $params['expireAt'] = Utils::fuckingJavaMicroTimestamp($params['expireAt']);
            return $this->createPrepareRequestWithPolicy($url, $policy, $params, 'GET', [], $headers);
        } else {
            return $this->createPrepareRequest($url, 'GET', $params, [], $headers, []);
        }
    }

    /**
     * 静态下载路径
     * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E5.AF.B9.E8.B1.A1.E6.97.81.E8.B7.AF.E4.BC.AA.E9.9D.99.E6.80.81.E4.B8.8B.E8.BD.BD_.5BGET.5D_.2Fv0.1.2Fdownload.2Fdirect.5B.7Bpath.7D.5D.5B.2F.7BdentryId.7D.5D.3F.5B.26session.3D.7Bsession.7D.5B.26token.3D.7Btoken.7D.26policy.3D.7Bpolicy.7D.26date.3D.7Bdate.7D.5D.5D.5B.26token.3D.7Btoken.7D.26policy.3D.7Bpolicy.7D.26expires.3D.7Bexpires.7D.5D.5B.26size.3D.7Bsize.7D.5D.5B.26attachment.3Dtrue.5D.5B.26name.3D.7Bname.7D.5D.5B.26ext.3D.7Bext.7D.5D.5B.26defaultImage.3D.7BdefaultImage.7D.5D
     */
    public function staticUrl(Policy $policy, $expiresIn = null, $params = [])
    {
        $policy->setServiceName($this->getServiceName());

        $url = '/v0.1/download/direct';
        if($policy->path) {
            $url .= Utils::quotePath($policy->path);
        } else {
            $url .= $policy->dentryId;
        }

        if($expiresIn) {
            $params['expireAt'] = time() + $expiresIn;
        }

        if($params['expireAt']) {
            $params['expireAt'] = Utils::fuckingJavaMicroTimestamp($params['expireAt']);
            return $this->createPrepareRequestWithPolicy($url, $policy, $params, 'GET', [])->uri;
        } else {
            return $this->createPrepareRequest($url, 'GET', $params, [], [], [])->uri;
        }
    }
}