<?php
namespace ND\SDP\CS;

use Shisa\HTTPClient\HTTP\Response;

/**
 * 会话标签
 * https://wiki.doc.101.com/index.php?title=内容服务all_new#.E4.BC.9A.E8.AF.9D.E6.A0.87.E7.AD.BE.EF.BC.9ASESSION
 * 
 * @property string $session uuid
 * @property string $service_id 服务id
 * @property string $path 授权路径
 * @property int $uid 用户id
 * @property string $role 角色
 * @property int $expire_at 过期时间(毫秒)
 * 
 * @property int $expires_in 在多少秒内过期
 */
class Session
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_READONLY = 'readonly';

    /**
     * 正常
     */
    const TYPE_COMMON = 0;
    /**
     * 临时,只能使用1次
     */
    const TYPE_TEMP = 1;

    private $_attrs = [
        'role' => Session::ROLE_USER
    ];

    public static function fromResponse(Response $response)
    {
        $rv = new static();
        $data = $response->json();
        foreach($data as $key => $value) {
            $rv->$key = $value;
        }
        return $rv;
    }

    public function __set($name, $value)
    {
        if($name == 'expires_in') {
            $this->expire_at = (time() + $value) * 1000;
        } else {
            $this->_attrs[$name] = $value;
        }
    }

    public function __get($name)
    {
        if($name == 'expires_in') {
            return intval($this->expire_at/1000) - time();
        } elseif(isset($this->_attrs[$name])) {
            return $this->_attrs[$name];
        }
    }
}