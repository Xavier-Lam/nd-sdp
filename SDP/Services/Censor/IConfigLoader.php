<?php
namespace ND\SDP\Services\Censor;

use ND\SDP\Auth\AuthBase;

/**
 * 敏感词配置加载
 */
interface IConfigLoader
{
    /**
     * 通过dentryId获得屏蔽字库
     * @return string[]
     */
    function getByDentryId($dentryId);

    /**
     * 获取敏感词分组
     * {
     *     "ugc": {
     *         "base_dentry_id": "",
     *         "base_group_code": ""
     *     }
     * }
     * @return array
     */
    function getGroups($version = 0);

    /**
     * 获取所有敏感词
     * @return string[]
     */
    function getPatterns($version = 0);
}