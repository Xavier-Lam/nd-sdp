<?php
namespace ND\SDP\Clients\Censor2;

use ND\SDP\Client\AuthClient;

class AppClient extends AuthClient
{
    /**
     * 获取产品配置和词库
     * https://wiki.doc.101.com/index.php?title=敏感词服务端API#.E8.8E.B7.E5.8F.96.E4.BA.A7.E5.93.81.E9.85.8D.E7.BD.AE.E5.92.8C.E8.AF.8D.E5.BA.93_.5BGET.5D.2Fc.2Fapps.2Fconfig.3Fversion.3D1
     */
    public function cAppConfig($appId, $version = 0)
    {
        return $this->sendWithAuth(
            '/v0.1/c/apps/config',
            ['version' => $version],
            'GET',
            [],
            ['sdp-app-id' => $appId]
        )->json();
    }

    /**
     * 获取产品配置和词库
     * https://wiki.doc.101.com/index.php?title=敏感词服务端API#.E8.8E.B7.E5.8F.96.E4.BA.A7.E5.93.81.E9.85.8D.E7.BD.AE.E5.92.8C.E8.AF.8D.E5.BA.93_.5BGET.5D.2Fs.2Fapps.2Fconfig.3Fversion.3D1
     */
    public function sAppConfig($version = 0)
    {
        return $this->sendWithAuth(
            '/v0.1/s/apps/config',
            ['version' => $version],
            'GET'
        )->json();
    }
}