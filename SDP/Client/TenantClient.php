<?php
namespace ND\SDP\Client;

use ND\SDP\Auth\BTSAuth;
use ND\SDP\Core\SdpTenant;

/**
 * 租户客户端
 */
class TenantClient extends AuthClient
{
    /**
     * @var SdpTenant
     */
    protected $tenant;

    public function __construct(BTSAuth $auth = null, SdpTenant $tenant)
    {
        parent::__construct($auth);
        $this->tenant = $tenant;
    }

    public function send($url, $method = 'GET', $data = [], $params = [], $headers = [], $extInfo = [])
    {
        // 租户路由流程
        // http://wiki.doc.101.com/index.php?title=租户开通与路由#.E7.A7.9F.E6.88.B7.E8.B7.AF.E7.94.B1.E6.B5.81.E7.A8.8B
        if($this->tenant->sdpApp && !isset($headers['sdp-app-id'])) {
            $headers['sdp-app-id'] = $this->tenant->sdpApp->sdpAppId;
        }

        if($this->tenant->sdpOrg && !isset($headers['sdp-org-id'])) {
            $headers['sdp-org-id'] = $this->tenant->sdpOrg->sdpOrgId;
        }

        if($this->tenant->bizType && !isset($headers['sdp-biz-type'])) {
            $headers['sdp-biz-type'] = $this->tenant->bizType;
        }

        if($this->tenant->tid && !isset($params['service_tenant_tid'])) {
            $params['service_tenant_tid'] = $this->tenant->tid;
        }

        return parent::send($url, $method, $data, $params, $headers, $extInfo);
    }

    protected function createChildClient($cls, $args = [])
    {
        $reflectedCls = new \ReflectionClass($cls);
        if($reflectedCls->isSubclassOf(TenantClient::class)) {
            $args = array_merge([$this->tenant], $args);
        }
        return parent::createChildClient($cls, $args);
    }
}