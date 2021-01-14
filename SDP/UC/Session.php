<?php
namespace ND\SDP\UC;

use ND\SDP\SdpApp;
use ND\SDP\SdpClient;
use ND\SDP\Utils;


/**
 * @property string $id
 * @property string $key
 */
class Session
{
    const SESSION_TTL = 1200;

    public $app;

    protected $device;

    protected $client;

    private $version;

    private $sessionId;

    private $sessionKey;

    public static function get(SdpApp $app, Device $device, $client = null, $version = 'v1')
    {
        return new static(null, null, $app, $device, $client, $version);
    }

    public static function current(SdpApp $app, $client = null, $version = 'v1')
    {
        return static::get($app, Device::current(), $client, $version);
    }

    public function __construct(
        $sessionId = null,
        $sessionKey = null,
        SdpApp $app = null,
        Device $device = null,
        SdpClient $client = null,
        $version = 'v1'
    ) {
        $this->sessionId = $sessionId;
        $this->sessionKey = $sessionKey;
        $this->app = $app;
        $this->device = $device;
        if($client) {
            $this->client = $client;
        } else {
            $this->client = new SdpClient();
            if($app && $app->env) {
                $this->client->setEnv($app->env);
            }
        }
        $this->version = $version;
    }

    public function getSessionId() {
        if(!$this->sessionId) {
            $key = static::getDeviceSessionName($this->app, $this->device, $this->version);
            $this->sessionId = Utils::getCookie($key);
            !$this->sessionId && $this->refresh();
        }
        return $this->sessionId;
    }

    public function getSessionKey() {
        if(!$this->sessionKey) {
            $key = static::getSessionKeyName($this->id);
            $this->sessionKey = Utils::getSession($key);
            !$this->sessionKey && $this->refresh();
        }
        return $this->sessionKey;
    }

    public function refresh() {
        $session = $this->client->uc->session->create($this->app, $this->device);
        $this->update($session->id, $session->key);
    }

    public function update($sessionId, $sessionKey)
    {
        $this->sessionId = $sessionId;
        $this->sessionKey = $sessionKey;

        $sessionName = static::getDeviceSessionName($this->app, $this->device, $this->version);
        Utils::setCookie($sessionName, $sessionId, static::SESSION_TTL);
        $sessionKeyName = static::getSessionKeyName($sessionId);
        Utils::setSession($sessionKeyName, $sessionKey);
    }

    /**
     * 解码经session加密的数据
     */
    public function decryptUserData($data)
    {
        // 传session则解密mac_key和account_id
        $sessionKey = $this->getSessionKey();
        $data['mac_key'] = Utils::decryptDes($data['mac_key'], $sessionKey);
        if($data['account_id']) {
            $data['account_id'] =  Utils::decryptDes($data['account_id'], $sessionKey);
        }
        if($data['user_id']) {
            $data['user_id'] =  Utils::decryptDes($data['user_id'], $sessionKey);
        }
        return $data;
    }

    private static function getDeviceSessionName(SdpApp $app, Device $device, $version = 'v1')
    {
        $deviceId = substr($device->id, 0, 8);
        return "sdp:$version:device:{$device->type}:$deviceId:{$app->sdpAppId}:session";
    }

    private static function getSessionKeyName($sessionId)
    {
        return "sdp:session:$sessionId:key";
    }

    public function __get($name)
    {
        if($name === 'id') {
            return $this->getSessionId();
        } elseif($name === 'key') {
            return $this->getSessionKey();
        }
    }
}