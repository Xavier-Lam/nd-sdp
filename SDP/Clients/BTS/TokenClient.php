<?php
namespace ND\SDP\Clients\BTS;

use ND\SDP\Auth\BTSAuth;
use ND\SDP\BTSApp;
use ND\SDP\Client\BaseClient;

class TokenClient extends BaseClient
{
    /**
     * 换取BTS Token
     * http://wiki.doc.101.com/index.php?title=BTSAPI#.5BPOST.5D.2Fbts_tokens_.E6.8D.A2.E5.8F.96BTS_Token
     */
    public function get(BTSApp $app, $raw = false)
    {
        $response = $this->send(
            '/v0.1/bts_tokens',
            'POST',
            [
                'app_name' => $app->name,
                'app_secret' => $app->secret
            ]
        );
        $data = $response->json();
        if($raw) {
            return $data;
        }
        
        return new BTSAuth($data['access_token'], $data['mac_key'], new \DateTime($data['expires_at']));
    }

    /**
     * Token校验
     * http://wiki.doc.101.com/index.php?title=BTSAPI#.5BPOST.5D.2Fbts_tokens.2F.7Bbts_token.7D.2Factions.2Fvalid_BTS_Token.E6.8E.88.E6.9D.83.E9.AA.8C.E8.AF.81
     */
    public function valid(BTSAuth $token, $host = '')
    {
        $host = $host?: parse_url($this->baseUrl, PHP_URL_HOST);
        $method = 'POST';
        $requestUri = '/';
        $sdpHeaders = ['sdp-app-id' => $this->app->sdpAppId];
        $mac = BTSAuth::createMac($token->macKey, $method, $requestUri, $host, $sdpHeaders);
        $response = $this->send(
            "/v0.1/bts_tokens/{$token->accessToken}/actions/valid",
            'POST',
            [
                'mac' => $mac,
                'http_method' => $method,
                'request_uri' => $requestUri,
                'host' => $host,
                'header_params' => $sdpHeaders
            ]
        );
        $data = $response->json();
        return $token->update($data);
    }
}