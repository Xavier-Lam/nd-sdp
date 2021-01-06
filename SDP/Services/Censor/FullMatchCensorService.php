<?php
namespace ND\SDP\Services\Censor;

/**
 * 完整匹配
 */
class FullMatchCensorService extends AbstractCensorService implements ICensorService
{
    public function testString($string): bool
    {
        return !!$this->highlight($string, 1);
    }

    public function highlight($string, $limit = null): array
    {
        $patterns = $this->configLoader->getPatterns();
        $rv = [];
        foreach($patterns as $pattern) {
            $pos = mb_strpos($string, $pattern);
            if($pos !== false) {
                $rv[] = [$pos, mb_strlen($pattern)];
                if($limit && count($rv) >= $limit) {
                    break;
                }
            }
        }
        return $rv;
    }
}