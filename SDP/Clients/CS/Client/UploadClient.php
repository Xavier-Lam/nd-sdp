<?php
namespace ND\SDP\Clients\CS\Client;

use ND\SDP\CS\Dentry;
use ND\SDP\CS\Policy;
use ND\SDP\CS\Utils;
use Shisa\HTTPClient\Auth\AbstractAuth;
use Shisa\HTTPClient\Formatters\FormDataFormatter;

/**
 * 文件上传
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E6.96.87.E4.BB.B6.E4.B8.8A.E4.BC.A0_.5BPOST.5D_.2Fv0.1.2Fupload.3Fsession.3D.7Bsession.7D.5B.26token.3D.7Btoken.7D.26policy.3D.7Bpolicy.7D.26date.3D.7Bdate.7D.5D.5B.26rename.3D.7Brename.7D.5D.5B.26regionName.3D.7BregionName.7D.5D.5B.26bucketName.3D.7BbucketName.7D.5D
 */
class UploadClient extends BaseCSClient
{
    public function __construct(AbstractAuth $auth = null)
    {
        parent::__construct($auth);
        $this->setFormatter(new FormDataFormatter());
    }

    /**
     * 一次性上传
     * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E4.B8.80.E6.AC.A1.E6.80.A7.E4.B8.8A.E4.BC.A0
     * 
     * @param array $data 请求内容
     * @param array $params 请求参数
     * @return Dentry
     */
    public function upload(Policy $policy, $file, $data = [], $params = [])
    {
        $policy->setServiceName($this->getServiceName());
        if($policy->path) {
            $data['name'] = basename($policy->path);
            $data['path'] = dirname($policy->path);
        }
        $data['scope'] = $policy->scope;
        $data['file'] = Utils::parseFile($file, $data['mime']?: '', $data['name']);
        $preparedRequest = $this->createPrepareRequestWithPolicy('/v0.1/upload', $policy, $data, 'POST', $params);
        $resp = $this->send($preparedRequest);
        return Dentry::fromResponse($resp);
    }
}