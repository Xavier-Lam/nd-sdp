<?php
namespace ND\SDP;

class Utils
{
    public static function getSession($key)
    {
        return $_SESSION[$key];
    }

    public static function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function getCookie($key)
    {
        return $_COOKIE[$key];
    }

    public static function setCookie($key, $value, $expire = 0)
    {
        if($expire) {
            $expire = $expire + time();
        }
        setcookie($key, $value, $expire, '/', '', false, true);
        $_COOKIE[$key] = $value;
    }

    public static function saltedMD5($content) {
        $salt = chr(163) . chr(172) . chr(161) . chr(163) . 'fdjf,jkgfkl';
        return md5($content . $salt);
    }

    public static function createRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function encryptDes($data, $key) {
        $paddedData = static::pad($data);
        $opts = OPENSSL_ZERO_PADDING | OPENSSL_RAW_DATA;
        return base64_encode(openssl_encrypt($paddedData, 'DES-ECB', $key, $opts));
    }

    public static function decryptDes($data, $key) {
        $data = base64_decode($data);
        $opts = OPENSSL_ZERO_PADDING | OPENSSL_RAW_DATA;
        return static::unpad(openssl_decrypt($data, 'DES-ECB', $key, $opts));
    }

    private static function pad($text)
    {
        $blockSize = 8;
        $length = strlen($text);
        $pad = $blockSize - ($length % $blockSize);
        return str_pad($text, $length + $pad, chr($pad));
    }

    private static function unpad($text)
    {
        $length = ord($text[strlen($text) - 1]);
        return substr($text, 0, -$length);
    }
}