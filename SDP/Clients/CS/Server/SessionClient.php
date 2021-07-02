<?php
namespace ND\SDP\Clients\CS\Server;

use ND\SDP\Client\AuthClient;
use ND\SDP\CS\Session;
use ND\SDP\CS\Utils;

/**
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E8.8E.B7.E5.8F.96session_.5BPOST.5D_.2Fv0.1.2Fsessions
 */
class SessionClient extends AuthClient
{
    /**
     * 获取session
     * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E6.B7.BB.E5.8A.A0.E6.9C.8D.E5.8A.A1_.5BPOST.5D_.2Fv0.1.2Fservices
     */
    public function create($serviceId, $path = '/', $expires = 1800, $role = Session::ROLE_USER, $uid = 0, $type = Session::TYPE_COMMON)
    {
        $path = Utils::fixPathRoot($path, $this->getAuth()->serviceName);
        $resp = $this->send('/v0.1/sessions', 'POST', [
            'service_id' => $serviceId,
            'path' => $path,
            'expires' => $expires,
            'role' => $role,
            'uid' => $uid,
            'type' => $type
        ]);
        return Session::fromResponse($resp);
    }
}