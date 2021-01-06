<?php
namespace ND\SDP\Services\Censor;

use ND\SDP\Auth\AuthBase;
use ND\SDP\SdpClient;
use Psr\SimpleCache\CacheInterface;

/**
 * 缓存敏感词配置加载
 */
class CacheConfigLoader extends BasicConfigLoader implements IConfigLoader
{
    protected $cache;
    protected $cacheConfig = [
        'version' => 'v1',
        // 敏感词库缓存时间 7*24*60*60
        'dentryCache' => 604800,
        // 敏感词组缓存时间 7*24*60*60
        'groupCache' => 604800
    ];

    /**
     * @param array $cacheConfig
     */
    public function __construct(SdpClient $client, $appId, CacheInterface $cache, $cacheConfig = null)
    {
        parent::__construct($client, $appId);
        $this->cache = $cache;
        $this->cacheConfig = ($cacheConfig?: []) + $this->cacheConfig;
    }

    public function getByDentryId($dentryId)
    {
        $expiresIn = $this->cacheConfig['dentryCache'];
        $cacheKey = $this->getCacheKey("sdp:censor:dentry:$dentryId", $expiresIn);
        $rv = $this->cache->get($cacheKey);
        if(!$rv) {
            $rv = parent::getByDentryId($dentryId);
            $this->cache->set($cacheKey, $rv, $expiresIn);
        }
        return $rv;
    }

    public function getGroups($version = 0)
    {
        $expiresIn = $this->cacheConfig['groupCache'];
        $cacheKey = $this->getCacheKey("sdp:censor:group:$this->appId", $expiresIn);
        $rv = $this->cache->get($cacheKey);
        if(!$rv) {
            $rv = parent::getGroups($version);
            $this->cache->set($cacheKey, $rv, $expiresIn);
        }
        return $rv;
    }

    protected function getCacheKey($prefix, $expiresIn = null)
    {
        $key = "$prefix:{$this->cacheConfig['version']}";
        if($expiresIn) {
            $key .= ':' . $expiresIn;
        }
        return $key;
    }
}