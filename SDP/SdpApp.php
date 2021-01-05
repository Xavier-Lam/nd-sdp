<?php
namespace ND\SDP;

/**
 * 应用,产品
 */
class SdpApp
{
    public $sdpAppId;

    public $env;

    private static $_registry = [];

    public static function singleton($sdpAppId, $env = null)
    {
        if(!isset(static::$_registry[$sdpAppId])) {
            static::$_registry[$sdpAppId] = new static($sdpAppId);
        }
        $rv = static::$_registry[$sdpAppId];
        if(!$rv->env && $env) {
            $rv->env = $env;
        }
        return $rv;
    }

    public function __construct($sdpAppId, $env = null)
    {
        $this->sdpAppId = $sdpAppId;
        $this->env = $env;
    }
}