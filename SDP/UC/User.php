<?php
namespace ND\SDP\UC;

use InvalidArgumentException;
use ND\SDP\Auth\MacAuth;
use ND\SDP\SdpApp;
use ND\SDP\SdpOrg;

class User extends MacAuth
{
    /**
     * 个人账户
     */
    const TYPE_PERSON = 'person';
    /**
     * 组织账户
     */
    const TYPE_ORG = 'org';

    /**
     * @var SdpApp
     */
    public $app;

    /**
     * @var SdpOrg
     */
    public $org;

    public $accountType;

    public $accountId;

    public $userId;

    /**
     * @var \DateTime
     */
    public $expiresAt;

    /**
     * 第三方参数
     */
    private $third = [];

    /**
     * 登录参数
     */
    private $loginInfo = null;

    /**
     * @param SdpApp $app
     */
    public static function getByData($data, ?SdpApp $app = null)
    {
        $uid = $data['account_type'] == static::TYPE_PERSON?
            $data['account_id']: $data['user_id'];
        $rv = new static($uid, $data['account_type'], $app);
        $rv->update($data);
        $rv->accountId = $data['account_id'];
        if(!$app && $data['app']) {
            $rv->app = SdpApp::singleton($data['app']['sdp-app-id'], $data['app']['env']);
        }

        if($data['third']) {
            $rv->third = $data['third'];
        }

        return $rv;
    }

    public function __construct(
        $uid = null,
        $accountType = User::TYPE_PERSON,
        SdpApp $app = null,
        $accessToken = null,
        $macKey = null,
        $refreshToken = null,
        $expiresAt = null,
        $accountId = null
    ) {
        $this->accountType = $accountType;
        if($accountType == static::TYPE_PERSON) {
            $this->accountId = $uid;
        } else {
            $this->userId = $uid;
            $this->accountId = $accountId;
        }
        $this->app = $app;

        parent::__construct($accessToken, $macKey, $refreshToken, $expiresAt);
    }

    public function auth()
    {
        if($this->loginInfo) {
            $data = $this->getClient()->uc->token->login(
                $this->loginInfo['session'],
                $this->loginInfo['loginname'],
                $this->loginInfo['password'],
                $this->loginInfo['orgCode'],
                '',
                $this->loginInfo['loginType'],
                $this->loginInfo['countryCode'],
                true
            );

            $this->update($data);
            if(isset($data['account_id']) && $this->accountId != $data['account_id']) {
                $this->accountId = $data['account_id'];
            }
            if(isset($data['user_id']) && $this->userId != $data['user_id']) {
                $this->userId = $data['user_id'];
            }

            $this->fire(static::EVENT_TOKENREFRESHED, $this, $data);
        } else {
            parent::auth();
        }
    }

    public function refresh()
    {
        try {
            parent::refresh();
        }
        catch(\Exception $e) {
            if($this->loginInfo) {
                // 对于有loginInfo的 尝试重新登录
                $this->auth();
                return;
            }
            throw $e;
        }
    }

    public function update($data)
    {
        $this->accessToken = $data['access_token'];
        $this->macKey = $data['mac_key'];
        $this->refreshToken = $data['refresh_token'];
        $this->expiresAt = new \DateTime($data['expires_at']);
    }

    /**
     * @param array $data {loginname, password, session, orgCode, loginType, countryCode}
     */
    public function setLoginInfo($data)
    {
        $this->loginInfo = $data + [
            'session' => Session::current($this->app, $this->getClient()),
            'orgCode' => '',
            'loginType' => '',
            'countryCode' => '+86'
        ];
    }

    public function getThird($key = null)
    {
        if($key && !isset($this->third[$key])) {
            throw new InvalidArgumentException('third key not exists');
        }
        return $key? $this->third[$key]: $this->third;
    }

    public function setThird($data)
    {
        $this->third = $data;
    }

    public function getData()
    {
        $rv = [
            'account_type' => $this->accountType,
            'account_id' => $this->accountId,
            'access_token' => $this->accessToken,
            'expires_at' => $this->expiresAt? $this->expiresAt->format('c'): null,
            'refresh_token' => $this->refreshToken,
            'mac_key' => $this->macKey
        ];

        if($this->app) {
            $rv['app'] = [
                'sdp-app-id' => $this->app->sdpAppId,
                'env' => $this->app->env
            ];
        }

        if($this->third) {
            $rv['third'] = $this->third;
        }

        return $rv;
    }
}