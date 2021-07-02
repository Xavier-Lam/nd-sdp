<?php
namespace ND\SDP\CS;

class Auth
{
    /**公钥 */
    public $accessKey;
    /**私钥 */
    public $secretKey;

    public static function create($accessKey, $secretKey)
    {
        $rv = new static();
        $rv->accessKey = $accessKey;
        $rv->secretKey = $secretKey;
        return $rv;
    }
}