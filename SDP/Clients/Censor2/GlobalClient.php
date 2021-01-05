<?php
namespace ND\SDP\Clients\Censor2;

use ND\SDP\Client\AuthClient;

/**
 * 运维管理台接口
 * https://wiki.doc.101.com/index.php?title=敏感词服务端API#.E8.BF.90.E7.BB.B4.E7.AE.A1.E7.90.86.E5.8F.B0.E6.8E.A5.E5.8F.A3
 */
class GlobalClient extends AuthClient
{
    /**
     * 获取公共词库列表
     * https://wiki.doc.101.com/index.php?title=敏感词服务端API#.E8.8E.B7.E5.8F.96.E5.85.AC.E5.85.B1.E8.AF.8D.E5.BA.93.E5.88.97.E8.A1.A8_.5BGET.5D.2Fglobal.2Flexicons.3F.24page.3D0.26.24size.3D20
     */
    public function lexicons($page = 0, $size = 200)
    {
        return $this->sendWithAuth('/v0.1/global/lexicons', ['$page' => $page, '$size' => $size])
            ->json();
    }
}