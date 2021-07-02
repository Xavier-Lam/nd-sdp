<?php
namespace ND\SDP\CS;

use ND\SDP\Exceptions\ResponseError;
use Shisa\HTTPClient\Auth\AbstractAuth;
use Shisa\HTTPClient\HTTP\PreparedRequest;
use Shisa\HTTPClient\HTTP\Request;

/**
 * https://wiki.doc.101.com/index.php?title=内容服务Token认证机制的算法说明
 */
class TokenFactory extends AbstractAuth
{
    /**租户名称 */
    public $serviceName;
    /**
     * @var Auth
     */
    public $auth;

    public static function create(Auth $auth, $serviceName)
    {
        $rv = new static();
        $rv->serviceName = $serviceName;
        $rv->auth = $auth;
        return $rv;
    }

    public function isInvalidAuthError($e)
    {
        return $e instanceof ResponseError &&
            in_array($e->errorCode, ['CS/TOKEN_EXPIRED']);
    }

    public function isAvailable()
    {
        return true;
    }

    public function auth()
    {
        
    }
    
    public function authRequestPostPrepare(PreparedRequest $preparedRequest, Request $request)
    {
        $preparedRequest = parent::authRequestPostPrepare($preparedRequest, $request);

        // 获取uriStr
        $parsedUri = parse_url($preparedRequest->uri);
        $uriStr = $parsedUri['path'];
        $query = [];
        parse_str($parsedUri['query'], $query);
        ksort($query);
        $queryStr = '';
        foreach($query as $k => $v) {
            if(!in_array($k, ['token', 'policy', 'expireAt', 'date'])) {
                $queryStr .= ($queryStr? '&': '?') . $k . '=' . $v;
            }
        }
        $uriStr .= $queryStr;

        // 获取uri中的date或expireAt,如果不存在,则补一下
        if(isset($request->params['expireAt'])) {
            $dateOrExpireAt = $request->params['expireAt'];
        } elseif(isset($request->params['date'])) {
            $dateOrExpireAt = $request->params['date'];
        } else {
            // 补date
            $dateOrExpireAt = gmdate('D, d M Y H:i:s T');
            $preparedRequest->uri .= ($query? '&': '?') . http_build_query(['date' => $dateOrExpireAt]);
        }

        // 计算token并加入url
        $token = $this->createToken($dateOrExpireAt, $uriStr, $request->method, $request->policy);
        $preparedRequest->uri .= '&' . http_build_query([
            'token' => $token,
            'policy' => Utils::fuckingJavaNoPaddingsUrlSafeBase64Encode($request->policy->toJson())
        ]);
        return $preparedRequest;
    }
    
    public function createToken($dateOrExpiresAt, $uriStr, $httpVerb, Policy $policy)
    {
        $signature = static::createSignature($this->auth->secretKey, $dateOrExpiresAt, $uriStr, $httpVerb, $policy);
        return "{$this->serviceName}:{$this->auth->accessKey}:$signature";
    }

    public static function createSignature($secretKey, $dateOrExpiresAt, $uriStr, $httpVerb, Policy $policy)
    {
        $strToSign = static::createStrToSign($dateOrExpiresAt, $uriStr, $httpVerb, $policy);
        $utf8EncodedStrToSign = mb_convert_encoding($strToSign, "utf-8");
        $hmacHash = hash_hmac('sha1', $utf8EncodedStrToSign, $secretKey, true);
        return Utils::fuckingJavaNoPaddingsUrlSafeBase64Encode($hmacHash);
    }

    public static function createStrToSign($dateOrExpiresAt, $uriStr, $httpVerb, Policy $policy)
    {
        return $dateOrExpiresAt . "\n" . $uriStr . "\n" . $httpVerb . "\n" . $policy->toJson();
    }
}