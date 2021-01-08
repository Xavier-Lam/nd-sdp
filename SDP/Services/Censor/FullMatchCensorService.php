<?php
namespace ND\SDP\Services\Censor;

/**
 * 完整匹配
 */
class FullMatchCensorService extends AbstractCensorService implements ICensorService
{
    protected function getMatchPos($string, $pattern): int
    {
        $pos = mb_strpos($string, $pattern);
        return $pos === false? -1: $pos;
    }

    protected function getMatchLength($string, $pattern, $pos): int
    {
        return mb_strlen($pattern);
    }
}