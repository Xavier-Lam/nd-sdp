<?php
namespace ND\SDP\Clients\Admin;

use ND\SDP\Client\AuthClient;

class AppClient extends AuthClient
{
    /**
     * 获取应用的信息
     * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口#.5BGET.5D_.2Fapps.2F.7Bsdp-app-id.7D_.E8.8E.B7.E5.8F.96.E5.BA.94.E7.94.A8.E7.9A.84.E4.BF.A1.E6.81.AF
     */
    public function info($sdpAppId)
    {
        return $this->sendWithAuth("/v1.1/apps/$sdpAppId")->json();
    }

    /**
     * 新增应用的第三方登录配置
     * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口#.5BPOST.5D_.2Fapps.2F.7Bsdp_app_id.7D.2Fthird-configs_.E6.96.B0.E5.A2.9E.E5.BA.94.E7.94.A8.E7.9A.84.E7.AC.AC.E4.B8.89.E6.96.B9.E7.99.BB.E5.BD.95.E9.85.8D.E7.BD.AE
     */
    public function addThirdConfigs($sdpAppId, $appType, $thirdAppId, $thirdAppSecret)
    {
        $data = [
            'third_app_id' => $thirdAppId,
            'third_app_key' => $thirdAppSecret,
            'third_plat_type' => $appType
        ];
        return $this->sendWithAuth("/v1.1/apps/$sdpAppId/third-configs", $data, 'POST')->json();
    }
}