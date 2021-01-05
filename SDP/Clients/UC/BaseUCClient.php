<?php
namespace ND\SDP\Clients\UC;

use ND\SDP\Client\AuthClient;
use ND\SDP\SdpApp;

class BaseUCClient extends AuthClient
{
    public function sendWithApp(SdpApp $app, $url, $method = 'GET', $data = [], $params = [], $headers = [], $options = [])
    {
        $headers['sdp-app-id'] = $app->sdpAppId;
        return $this->send($url, $method, $data, $params, $headers, $options);
    }

    public function sendWithAppAuth(SdpApp $app, $url, $method = 'GET', $data = [], $params = [], $headers = [], $options = [])
    {
        $headers['sdp-app-id'] = $app->sdpAppId;
        return $this->sendWithAuth($url, $data, $method, $params, $headers, $options);
    }
}