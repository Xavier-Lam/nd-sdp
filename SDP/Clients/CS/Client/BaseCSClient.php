<?php
namespace ND\SDP\Clients\CS\Client;

use ND\SDP\Client\AuthClient;
use ND\SDP\CS\Policy;
use Shisa\HTTPClient\HTTP\Request;

/**
 * 文件上传
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E6.96.87.E4.BB.B6.E4.B8.8A.E4.BC.A0_.5BPOST.5D_.2Fv0.1.2Fupload.3Fsession.3D.7Bsession.7D.5B.26token.3D.7Btoken.7D.26policy.3D.7Bpolicy.7D.26date.3D.7Bdate.7D.5D.5B.26rename.3D.7Brename.7D.5D.5B.26regionName.3D.7BregionName.7D.5D.5B.26bucketName.3D.7BbucketName.7D.5D
 */
class BaseCSClient extends AuthClient
{
    // public function sendWithPolicy($url, Policy $policy, $data = [], $method = 'GET', $params = [], $headers = [], $options = [])
    // {
    //     $options['policy'] = $policy;
    //     return $this->sendWithAuth(
    //         $url,
    //         $data,
    //         $method,
    //         $params,
    //         $headers,
    //         $options
    //     );
    // }

    // protected function createRequest($url, $method = 'GET', $data = [], $params = [], $headers = [], $options = [])
    // {
    //     $policy = null;
    //     if(array_key_exists('policy', $options)) {
    //         $policy = $options['policy'];
    //         unset($options['policy']);
    //     }
    //     $request = parent::createRequest($url, $method, $data, $params, $headers, $options);
    //     $request->policy = $policy;
    //     return $request;
    // }

    public function createPrepareRequestWithPolicy($url, Policy $policy, $data = [], $method = 'GET', $params = [], $headers = [], $options = [])
    {
        $options['policy'] = $policy;
        $options['auth'] = true;
        return $this->createPrepareRequest($url, $method, $data, $params, $headers, $options);
    }

    public function prepare(Request $request, $options = [])
    {
        if(isset($options['policy'])) {
            $request->policy = $options['policy'];   
        }
        return parent::prepare($request, $options);
    }

    protected function getServiceName()
    {
        return $this->getAuth()->serviceName;
    }
}