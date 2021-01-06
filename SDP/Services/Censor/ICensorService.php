<?php
namespace ND\SDP\Services\Censor;

/**
 * 敏感词服务
 */
interface ICensorService
{
    /**
     * 测试是否包含敏感词 真值说明包含
     */
    function testString($string): bool;

    /**
     * 打码
     */
    function censor($string, $replace = '*'): string;

    /**
     * 标注敏感词
     * @param int $limit 最多选取几个 空值不限
     * @return array[] [[开始位置,长度],[开始位置,长度]...]
     */
    function highlight($string, $limit = null): array;
}