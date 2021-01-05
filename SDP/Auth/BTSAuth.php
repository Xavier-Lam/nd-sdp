<?php
namespace ND\SDP\Auth;

use ND\SDP\BTS\Token;
use ND\SDP\Exceptions\ResponseError;
use Shisa\HTTPClient\HTTP\PreparedRequest;
use Shisa\HTTPClient\HTTP\Request;

/**
 * BTS授权
 */
class BTSAuth extends AuthBase
{
    public $accessToken;

    public $macKey;

    public $expiresAt;

    public static function get($accessToken, $macKey, $expiresAt)
    {
        return new static($accessToken, $macKey, $expiresAt);
    }

    public function __construct($accessToken, $macKey, $expiresAt) {
        $this->accessToken = $accessToken;
        $this->macKey = $macKey;
        $this->expiresAt = $expiresAt;
    }

    public function isAvailable()
    {
        return !!$this->accessToken && !!$this->macKey;
    }

    public function getClient()
    {
        $client = parent::getClient();
        if(!$client) {
            throw new \RuntimeException('client has not been set');
        }
        return $client;
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
        $headers = $preparedRequest->headers;
        ksort($headers);
        $sdpHeaders = [];
        foreach($headers as $header) {
            if(substr(strtolower($header), 0, 4) === 'sdp-') {
                $value = trim(explode(':', $header, 2)[1]);
                $sdpHeaders[] = $value;
            }
        }

        $uri = $request->path;
        $query = parse_url($preparedRequest->uri, PHP_URL_QUERY);
        if($query) {
            $uri .= '?' . $query;
        }
        $mac = static::createMac(
            $this->macKey,
            $preparedRequest->method,
            $uri,
            parse_url($preparedRequest->uri, PHP_URL_HOST),
            $sdpHeaders
        );

        return static::createAuthorizationStr('BTS', [
            'id' => $this->accessToken,
            'mac' => $mac
        ]);
    }

    public function isInvalidAuthError($e)
    {
        return $e instanceof ResponseError &&
            in_array($e->errorCode, ['BTS/AUTH_INVALID_TOKEN', 'BTS/AUTH_TOKEN_EXPIRED']);
    }

    public function update($data)
    {
        $this->accessToken = $data['access_token'];
        $this->macKey = $data['mac_key'];
        $this->expiresAt = $data['expires_at'];

        $this->fire(static::EVENT_TOKENREFRESHED, $this, $data);
    }

    public static function createMac($macKey, $method, $uri, $host, $sdpHeaders)
    {
        $headers = array_merge([$method, $uri, $host], array_values($sdpHeaders));
        $cannonHeaders = implode("\n", $headers) . "\n";
        return base64_encode(hash_hmac('sha256', $cannonHeaders, $macKey, true));
    }
}