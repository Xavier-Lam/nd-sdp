<?php
namespace ND\SDP\Clients\UC;

use ND\SDP\SdpApp;
use ND\SDP\UC\Session;
use ND\SDP\UC\User;
use ND\SDP\Utils;

class TokenClient extends BaseUCClient
{
    const LOGINTYPE_MOBILE = 'mobile';
    const LOGINTYPE_EMAIL = 'email';
    const LOGINTYPE_USERNAME = 'user_name';

    /**
     * 密码登录
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Ftokens_.E5.AF.86.E7.A0.81.E7.99.BB.E5.BD.95
     */
    public function login(Session $session, $loginname, $password, $orgCode = '', $identifyCode = '', $loginType = '', $countryCode = '+86', $raw = false)
    {
        $sessionId = $session->getSessionId();
        $sessionKey = $session->getSessionKey();
        $data = $this->sendWithApp(
            $session->app,
            '/v1.1/tokens',
            'POST',
            [
                'login_name_type' => $loginType,
                'login_name' => Utils::encryptDes($loginname, $sessionKey),
                'country_code' => $countryCode,
                'org_code' => $orgCode,
                'password' => Utils::encryptDes(Utils::saltedMD5($password), $sessionKey),
                'session_id' => $sessionId,
                'identify_code' => $identifyCode
            ]
        )->json();

        $data = $this->decryptUserData($session, $data);

        if($raw) {
            return $data;
        } else {
            $auth = User::getByData($data, $session->app);
            $auth->setClient($this->getBaseClient());
            return $auth;
        }
    }

    /**
     * 短信验证码登录
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Fsms_tokens_.E7.9F.AD.E4.BF.A1.E7.99.BB.E5.BD.95
     */
    public function smsLogin(Session $session, $tel, $smsCode, $countryCode = '+86') {
        $sessionId = $session->getSessionId();
        $data = $this->sendWithApp(
            $session->app,
            '/v1.1/sms_tokens',
            'POST',
            [
                'session_id' => $sessionId,
                'country_code' => $countryCode,
                'mobile' => $tel,
                'sms_code' => $smsCode
            ]
        )->json();

        $data = $this->decryptUserData($session, $data);

        $auth = User::getByData($data, $session->app);
        $auth->setClient($this->getBaseClient());
        return $auth;
    }

    /**
     * 令牌检查
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Ftokens.2F.7Baccess_token.7D.2Factions.2Fvalid_.E4.BB.A4.E7.89.8C.E6.A3.80.E6.9F.A5
     */
    public function valid(User $user, SdpApp $app = null)
    {
        $host = parse_url($this->baseUrl, PHP_URL_HOST);
        $nonce = User::createNonce();
        $method = 'POST';
        $requestUri = '/';
        $sdpHeaders = [];
        $mac = User::createMac($nonce, $method, $requestUri, $host, $sdpHeaders);
        $response = $this->sendWithApp(
            $app?: $user->app,
            "/v1.1/tokens/{$user->accessToken}/actions/valid",
            'POST',
            [
                'mac' => $mac,
                'nonce' => $nonce,
                'http_method' => $method,
                'request_uri' => $requestUri,
                'host' => $host,
                'header_params' => $sdpHeaders
            ]
        );
        $data = $response->json();
        $user->update($data);
        $user->setClient($this->getBaseClient());
        return $user;
    }

    /**
     * 更新令牌
     * UC/AUTH_TOKEN_EXPIRED时调用
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Ftokens.2F.7Brefresh_token.7D.2Factions.2Frefresh_.E4.BB.A4.E7.89.8C.E7.BB.AD.E7.BA.A6
     * 
     * @param SdpApp $app 组织账户必传
     */
    public function refresh(User $user, SdpApp $app = null) {
        $data = $this->sendWithApp(
            $app?: $user->app,
            "/v1.1/tokens/{$user->refreshToken}/actions/refresh",
            'POST'
        )->json();

        $user->update($data);
        $user->setClient($this->getBaseClient());
        return $data;
    }

    /**
     * 登出
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BDELETE.5D.2Ftokens.2F.7Baccess_token.7D_.E7.99.BB.E5.87.BA
     */
    public function logout(User $user, SdpApp $app = null) {
        return $this->sendWithApp(
            $app?: $user->app,
            "/v1.1/tokens/{$user->accessToken}",
            "DELETE"
        );
    }

    /**
     * 解码经session加密的数据
     */
    public function decryptUserData(Session $session, $data)
    {
        // 传session则解密mac_key和account_id
        $sessionKey = $session->getSessionKey();
        $data['mac_key'] = Utils::decryptDes($data['mac_key'], $sessionKey);
        if($data['account_id']) {
            $data['account_id'] =  Utils::decryptDes($data['account_id'], $sessionKey);
        }
        if($data['user_id']) {
            $data['user_id'] =  Utils::decryptDes($data['user_id'], $sessionKey);
        }
        return $data;
    }
}