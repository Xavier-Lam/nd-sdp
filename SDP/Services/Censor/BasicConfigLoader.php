<?php
namespace ND\SDP\Services\Censor;

use ND\SDP\SdpClient;
use Shisa\HTTPClient\Clients\HTTPClient;

/**
 * 基础敏感词配置加载(每次重新请求)
 */
class BasicConfigLoader implements IConfigLoader
{
    protected $client;
    protected $appId;
    protected $appGroups = [];
    protected $dentries = [];

    /**
     * @param SdpClient $client 需已授权
     */
    public function __construct(SdpClient $client, $appId)
    {
        $this->client = $client;
        $this->appId = $appId;
    }

    public function getByDentryId($dentryId)
    {
        if(!isset($this->dentries[$dentryId])) {
            $client = new HTTPClient();
            $url = 'https://cs.101.com/v0.1/download';
            $resp = $client->send($url, 'GET', ['dentryId' => $dentryId]);
            $this->dentries[$dentryId] = explode("\n", $resp->content);
        }
        return $this->dentries[$dentryId];
    }

    public function getGroups($version = 0)
    {
        if(!isset($this->appGroups[$this->appId])) {
            $data = $this->client->censor2->app->cAppConfig($this->appId);
            $this->appGroups[$this->appId] = $data['lexicon_groups'];
        }
        return $this->appGroups[$this->appId];
    }

    public function getPatterns($version = 0)
    {
        $groups = $this->getGroups();
        $patterns = [];
        foreach($groups as $group) {
            $patterns = array_merge($patterns, $this->getByDentryId($group['base_dentry_id']));
        }
        return array_values(array_unique(array_filter($patterns)));
    }
}