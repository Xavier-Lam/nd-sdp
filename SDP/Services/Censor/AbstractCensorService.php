<?php
namespace ND\SDP\Services\Censor;

abstract class AbstractCensorService implements ICensorService
{
    protected $configLoader;

    public function __construct(IConfigLoader $configLoader)
    {
        $this->configLoader = $configLoader;
    }
    
    public function testString($string): bool
    {
        return !!$this->highlight($string, 1);
    }

    public function censor($string, $replace = '*'): string
    {
        $highlights = $this->highlight($string);
        foreach($highlights as $highlight) {
            list($start, $length) = $highlight;
            $string = mb_substr($string, 0, $start)
                        . str_repeat($replace, $length)
                        . mb_substr($string, $start + $length);
        }
        return $string;
    }

    public function highlight($string, $limit = null): array
    {
        $patterns = $this->configLoader->getPatterns();
        $rv = [];
        foreach($patterns as $pattern) {
            $pos = $this->getMatchPos($string, $pattern);
            if($pos >= 0) {
                $rv[] = [$pos, $this->getMatchLength($string, $pattern, $pos)];
                if($limit && count($rv) >= $limit) {
                    break;
                }
            }
        }
        return $rv;
    }

    public function getMatches($string, $limit = null): array
    {
        $patterns = $this->configLoader->getPatterns();
        $rv = [];
        foreach($patterns as $pattern) {
            $pos = $this->getMatchPos($string, $pattern);
            if($pos >= 0) {
                $rv[] = $pattern;
                if($limit && count($rv) >= $limit) {
                    break;
                }
            }
        }
        return $rv;
    }

    /**
     * 获取命中敏感词的位置
     * @return 开始命中的位置,-1标识未命中
     */
    protected abstract function getMatchPos($string, $pattern): int;

    /**
     * 获取敏感词覆盖长度
     * @return 长度
     */
    protected abstract function getMatchLength($string, $pattern, $pos): int;
}