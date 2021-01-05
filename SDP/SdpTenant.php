<?php
namespace ND\SDP;

/**
 * 租户
 *
 * http://reference.doc.101.com/appfactory/userguide/#/business-component/service/service-guide
 */
class SdpTenant
{
    public $bizType;

    public $tid;

    /**
     * @var SdpApp
     */
    public $sdpApp;

    /**
     * @var SdpOrg
     */
    public $sdpOrg;

    public static function byApp(SdpApp $app = null)
    {
        $rv = new static();
        $rv->sdpApp = $app;
        return $rv;
    }

    public static function byOrg(SdpOrg $org, SdpApp $app = null)
    {
        $rv = new static();
        $rv->sdpOrg = $org;
        if($app) {
            $rv->sdpApp = $app;
        } else {
            $rv->sdpApp = $org->sdpApp;
        }
        return $rv;
    }

    public static function byTid($tid, SdpApp $app = null)
    {
        $rv = new static();
        $rv->tid = $tid;
        $rv->sdpApp = $app;
        return $rv;
    }

    public static function byBizType(SdpApp $app, $bizType, SdpOrg $org = null)
    {
        $rv = new static();
        $rv->sdpApp = $app;
        $rv->bizType = $bizType;
        $rv->sdpOrg = $org;
        return $rv;
    }

    private function __construct()
    {
    }
}