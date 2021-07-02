<?php
namespace ND\SDP\CS;

class Utils
{
    /**
     * @return \CURLFile
     */
    public static function parseFile($file, $mimetype = '', $filename = '')
    {
        return new \CURLFile($file, $mimetype, $filename);
    }

    public static function fuckingJavaNoPaddingsUrlSafeBase64Encode($str)
    {
        return strtr(rtrim(base64_encode($str), "="), '+/', '-_');
    }

    public static function fuckingJavaMicroTimestamp($t)
    {
        return $t*1000;
    }

    public static function quotePath($path)
    {
        return implode('/', array_map('urlencode', explode('/', $path)));
    }

    public static function fixPathRoot($path, $serviceName)
    {
        // 修正path
        $parts = explode('/', $path);
        if($parts[1] != $serviceName) {
            $path = "/$serviceName" . $path;
        }
        return $path;
    }
}