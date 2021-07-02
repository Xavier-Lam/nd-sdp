<?php
namespace ND\SDP\CS;

use ND\SDP\Exceptions\ResponseError;
use Shisa\HTTPClient\Auth\AbstractAuth;
use Shisa\HTTPClient\HTTP\Request;

/**
 * https://wiki.doc.101.com/index.php?title=内容服务Token认证机制的算法说明
 */
class SessionFactory extends AbstractAuth
{
    /**租户名称 */
    public $serviceName;
    /**租户id */
    public $serviceId;

    private $_session;

    public static function create($serviceId, $serviceName)
    {
        $rv = new static();
        $rv->serviceName = $serviceName;
        $rv->serviceId = $serviceId;
        return $rv;
    }

    public function isAvailable()
    {
        return !!$this->getSession();
    }

    public function isInvalidAuthError($e)
    {
        return $e instanceof ResponseError &&
            in_array($e->errorCode, ['CS/SESSION_NOT_FOUND', 'CS/SESSION_EXPIRED']);
    }

    public function auth()
    {
        /**@var \ND\SDP\Clients\CS\Server\SessionClient */
        $client = $this->getClient();
        $session = $client->session->create($this->serviceId, '/', 7200, Session::ROLE_ADMIN);
        $this->setSession($session);
    }

    public function authRequest(Request $request)
    {
        // 在请求参数中加入session
        $request->params['session'] = $this->getSession()->session;
        return $request;
    }

    protected function setSession(Session $session)
    {
        $this->_session = $session;
    }

    /**
     * @return Session
     */
    protected function getSession()
    {
        return $this->_session;
    }
}