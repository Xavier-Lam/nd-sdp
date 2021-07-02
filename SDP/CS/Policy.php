<?php
namespace ND\SDP\CS;

/**
 * 上传策略
 */
class Policy
{
    const SCOPE_PRIVATE = 0;
    const SCOPE_PUBLIC = 1;

    const TYPE_UPLOAD = 'upload';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_MANAGE = 'manage';

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_READONLY = 'readonly';

    public $policyType;
    public $path;
    public $dentryId;
    public $uid;
    public $role;
    public $scope;

    public static function createByPath($path, $scope = Policy::SCOPE_PRIVATE, $type = Policy::TYPE_DOWNLOAD, $role = Policy::ROLE_USER, $uid = 0)
    {
        $policy = new static();
        $policy->path = $path;
        $policy->scope = $scope;
        $policy->policyType = $type;
        $policy->uid = $uid;
        $policy->role = $role;
        return $policy;
    }

    public static function createByDentry($dentryId, $scope = Policy::SCOPE_PRIVATE, $type = Policy::TYPE_DOWNLOAD, $role = Policy::ROLE_USER, $uid = 0)
    {
        $policy = new static();
        $policy->scope = $scope;
        $policy->policyType = $type;
        $policy->dentryId = $dentryId;
        $policy->uid = $uid;
        $policy->role = $role;
        return $policy;
    }

    public function setServiceName($serviceName)
    {
        if($this->path && $serviceName) {
            // 修正path
            $this->path = Utils::fixPathRoot($this->path, $serviceName);
        }
    }

    public function toArray()
    {
        $rv = [
            'policyType' => $this->policyType,
            'uid' => $this->uid,
            'role' => $this->role,
            'scope' => $this->scope
        ];
        if($this->dentryId) {
            $rv['dentryId'] = $this->dentryId;
        } else {
            $rv['path'] = $this->path;
        }
        return $rv;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}