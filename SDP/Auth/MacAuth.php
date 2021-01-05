<?php
namespace ND\SDP\Auth;

use DateTime;
use ND\SDP\Exceptions\ResponseError;
use ND\SDP\Utils;
use Shisa\HTTPClient\HTTP\PreparedRequest;
use Shisa\HTTPClient\HTTP\Request;

/**
 * Mac授权
 */
class MacAuth extends AuthBase
{
    public $accessToken;

    public $macKey;

    public $refreshToken;

    public $expiresAt;

    public function __construct($accessToken, $macKey, $refreshToken, \DateTime $expiresAt = null)
    {
        $this->accessToken = $accessToken;
        $this->macKey = $macKey;
        $this->refreshToken = $refreshToken;
        $this->expiresAt = $expiresAt;
    }

    public function isAvailable()
    {
        $available = !!$this->accessToken && !!$this->macKey;
        if($available && $this->expiresAt) {
            return new DateTime() < $this->expiresAt;
        }
        return $available;
    }

    public function isInvalidAuthError($e)
    {
        return $e instanceof ResponseError &&
                in_array($e->errorCode, ['UC/AUTH_TOKEN_EXPIRED', 'UC/AUTH_INVALID_TOKEN']);
    }

    public function refresh()
    {
        $data = $this->getClient()->uc->token->refresh($this);
        $this->fire(static::EVENT_TOKENREFRESHED, $this, $data);
    }

    public function auth()
    {
        throw new \RuntimeException('Not Implemented', -1);
    }

    public function authRequestPostPrepare(PreparedRequest $preparedRequest, Request $request)
    {
        $auth = $this->createAuth($preparedRequest, $request);
        $preparedRequest->headers[] = "Authorization: $auth";
        return $preparedRequest;
    }

    public function createAuth(PreparedRequest $preparedRequest, Request $request)
    {
        $nonce = static::createNonce();
        $method = $request->method;
        $uri = $request->path;
        $query = parse_url($preparedRequest->uri, PHP_URL_QUERY);
        if($query) {
            $uri .= '?' . $query;
        }
        $host = $request->host;

        return static::createAuthorizationStr('MAC', [
            'id' => $this->accessToken,
            'nonce' => $nonce,
            'mac' => static::createMac($nonce, $method, $uri, $host, $this->macKey)
        ]);
    }

    public static function createMac($nonce, $method, $uri, $host, $macKey)
    {
        $headers = [
            $nonce,
            $method,
            $uri,
            $host
        ];
        $cannonHeaders = implode("\n", $headers) . "\n";
        return base64_encode(hash_hmac('sha256', $cannonHeaders, $macKey, true));
    }

    public static function createNonce()
    {
        $timestamp = time() * 1000;
        $randStr = Utils::createRandomString(8);
        return $timestamp . ':' . $randStr;
    }
}