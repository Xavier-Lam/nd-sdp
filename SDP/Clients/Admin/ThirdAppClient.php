<?php
namespace ND\SDP\Clients\Admin;

use ND\SDP\Client\AuthClient;

class ThirdAppClient extends AuthClient
{
    /**
     * 新增应用的第三方应用信息配置
     * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口#.5BPOST.5D_.2FthirdApps.2F.7Bsdp_app_id.7D.2Fthird-configs_.E6.96.B0.E5.A2.9E.E5.BA.94.E7.94.A8.E7.9A.84.E7.AC.AC.E4.B8.89.E6.96.B9.E5.BA.94.E7.94.A8.E4.BF.A1.E6.81.AF.E9.85.8D.E7.BD.AE
     */
    public function addThirdConfigs($sdpAppId, $appType, $thirdAppId, $thirdAppSecret, $redirectUri = '')
    {
        $data = [
            'third_plat_app_id' => $thirdAppId,
            'third_plat_app_secret' => $thirdAppSecret,
            'third_plat_app_type' => $appType
        ];
        $redirectUri && $data['redirect_uri'] = $redirectUri;
        return $this->sendWithAuth("/v1.1/thirdApps/$sdpAppId/third-configs", $data, 'POST')->json();
    }

    /**
     * 修改应用的第三方应用信息配置
     * https://wiki.doc.101.com/index.php?title=身份认证领域-管理端接口#.5BPATCH.5D_.2FthirdApps.2F.7Bsdp_app_id.7D.2Fthird-configs.2F.7Bapp_id.7D_.E4.BF.AE.E6.94.B9.E5.BA.94.E7.94.A8.E7.9A.84.E7.AC.AC.E4.B8.89.E6.96.B9.E5.BA.94.E7.94.A8.E4.BF.A1.E6.81.AF.E9.85.8D.E7.BD.AE
     */
    public function patchThirdConfigs($sdpAppId, $thirdAppId, $thirdAppSecret = '', $update = [])
    {
        $thirdAppSecret && $update['third_plat_app_secret'] = $thirdAppSecret;
        return $this->sendWithAuth(
            "/v1.1/thirdApps/$sdpAppId/third-configs/$thirdAppId",
            $update,
            'PATCH'
        )->json();
    }
}