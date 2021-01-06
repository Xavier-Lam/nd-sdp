<?php
namespace ND\SDP\Services\Censor;

abstract class AbstractCensorService implements ICensorService
{
    protected $configLoader;

    public function __construct(IConfigLoader $configLoader)
    {
        $this->configLoader = $configLoader;
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
}