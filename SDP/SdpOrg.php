<?php
namespace ND\SDP;

/**
 * 组织
 *
 * 一个app对应一个机构
 * 一个机构可对应多个app
 * 一个组织可对应多个机构
 */
class SdpOrg
{
    public $sdpOrgId;

    public $sdpApp;

    private static $_registry = [];

    public static function singleton($sdpOrgId, SdpApp $app = null)
    {
        $key = ($app? $app->sdpAppId: '') . '/' . $sdpOrgId;
        if(!isset(static::$_registry[$key])) {
            static::$_registry[$key] = new static($sdpOrgId, $app);
        }
        return static::$_registry[$key];
    }

    public function __construct($sdpOrgId, SdpApp $app = null)
    {
        $this->sdpOrgId = $sdpOrgId;
        $this->sdpApp = $app;
    }
}