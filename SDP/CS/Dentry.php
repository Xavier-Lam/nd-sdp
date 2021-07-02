<?php
namespace ND\SDP\CS;

use Shisa\HTTPClient\HTTP\Response;

/**
 * 目录项
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E7.9B.AE.E5.BD.95.E9.A1.B9.EF.BC.9ADENTRY
 * 
 * @property string $dentry_id 目录项id（UUID）
 * @property string $service_id 服务id（UUID）
 * @property string $parent_id 父目录项id（UUID）
 * @property string $path 目录项路径（文件包括扩展名）
 * @property int $type 类型：0=目录 1=文件  （ 2=连接文件，CS1.8不存在连接文件，只在秒传接口的响应中做兼容）
 * @property string $name 目录项名（文件一般包括扩展名）
 * @property string $other_name 备注名
 * @property array $info 自定义元数据，如：{tags: [xx,xx], title: xx, note: xx, content: xx}
 * @property int $scope 公开范围：0=私密 1=完全公开 （2=需要访问密码，CS1.8不存在该值）
 * @property int $uid 拥有者uid/上传者uid（建议业务方使用info字段来保存和使用uid信息）
 * @property int $create_at 创建时间，时间戳，毫秒
 * @property int $update_at 最后更新时间，时间戳，毫秒
 * @property int $expire_at 过期时间，时间戳，毫秒
 * @property string $user_region 旁路上传初始化和一次性上传时，返回服务器识别出的用户所在区域
 */
class Dentry
{
    public static function fromResponse(Response $response)
    {
        $rv = new static();
        $data = $response->json();
        foreach($data as $key => $value) {
            $rv->$key = $value;
        }
        return $rv;
    }
}