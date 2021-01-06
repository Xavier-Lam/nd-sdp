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
        $client = new HTTPClient();
        $url = 'https://cs.101.com/v0.1/download';
        $resp = $client->send($url, 'GET', ['dentryId' => $dentryId]);
        return explode("\n", $resp->content);
    }

    public function getGroups($version = 0)
    {
        $data = $this->client->censor2->app->cAppConfig($this->appId);
        return $data['lexicon_groups'];
    }

    public function getPatterns($version = 0)
    {
        $groups = $this->getGroups();
        $patterns = [];
        foreach($groups as $group) {
            $patterns = array_merge($patterns, $this->getByDentryId($group['base_dentry_id']));
        }
        return array_values(array_unique($patterns));
    }
}