<?php
namespace ND\SDP\Clients\UC;

use ND\SDP\SdpApp;
use ND\SDP\UC\Device;
use ND\SDP\UC\Session;

class SessionClient extends BaseUCClient
{
    /**
     * 创建会话
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Fsessions_.E5.88.9B.E5.BB.BA.E4.BC.9A.E8.AF.9D
     */
    public function create(SdpApp $app, Device $device, $version = 'v1') {
        $deviceId = $device->encryptedId();
        $data = $this->sendWithApp(
            $app,
            '/v1.1/sessions',
            'POST',
            ['device_id' => $deviceId]
        )->json();

        return new Session(
            $data['session_id'],
            $data['session_key'],
            $app,
            $device,
            $this->getBaseClient(),
            $version
        );
    }

    /**
     * 发送短信验证码
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BPOST.5D.2Fsessions.2F.7Bsession_id.7D.2Factions.2Fsend_sms_code_.E4.B8.8B.E5.8F.91.E7.9F.AD.E4.BF.A1.E9.AA.8C.E8.AF.81.E7.A0.81
     */
    public function sendSMSCode(Session $session, $opType, $tel, $identifyCode = '', $countryCode = '+86') {
        $sessionId = $session->getSessionId();
        return $this->sendWithApp(
            $session->app,
            "/v1.1/sessions/$sessionId/actions/send_sms_code",
            'POST',
            [
                'op_type' => $opType,
                'country_code' => $countryCode,
                'mobile' => $tel,
                'identify_code' => $identifyCode
            ]
        )->json();
    }

    /**
     * 获取图片验证码
     * http://wiki.doc.101.com/index.php?title=身份认证领域-前端接口#.5BGET.5D.2Fsessions.2F.7Bsession_id.7D.2Fidentify_code_.E8.8E.B7.E5.8F.96.E5.9B.BE.E7.89.87.E9.AA.8C.E8.AF.81.E7.A0.81
     */
    public function identifyCode(Session $session) {
        $sessionId = $session->getSessionId();
        return $this->sendWithApp(
            $session->app,
            "/v1.1/sessions/$sessionId/identify_code"
        );
    }
}