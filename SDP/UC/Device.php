<?php
namespace ND\SDP\UC;

use ND\SDP\Utils;

class Device
{
    const ANDROID = 'a';
    const IOS = 'i';
    const WEB = 'w';

    public $id;

    public $type;

    private $_sessions = [];

    public static function current($expiresIn = 0, $version = 'v1')
    {
        $key = "sdp:$version:device";
        $id = Utils::getCookie($key);
        if(!$id) {
            // 通过客户端信息协助生成id
            $ua = $_SERVER['HTTP_USER_AGENT'];
            $remoteAddr = $_SERVER['REMOTE_ADDR'];
            $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $now = time();
            $random = mt_rand();
            $userStr = base64_encode(md5($ua . $remoteAddr . $forwardedFor . $now . $random, true));

            $randomStr = Utils::createRandomString(96);
            $id = $randomStr . $userStr;
            $expiresAt = $expiresIn? (time() + $expiresIn): 0;
            Utils::setCookie($key, $id, $expiresAt);
        }
        return new static($id, static::WEB);
    }

    public function encryptedId()
    {
        $encryptedDeviceId = base64_encode(Utils::saltedMD5($this->id));
        $checkChar = substr($encryptedDeviceId, 2, 1);
        return $checkChar . $this->type . $this->id;
    }

    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * @return Session
     */
    public function session($app, $client = null, $version = 'v1')
    {
        $key = "{$app->sdpAppId}:$version";
        if(!isset($this->_sessions[$key])) {
            $this->_sessions[$key] = Session::get($app, $this, $client, $version);
        }
        return $this->_sessions[$key];
    }
}