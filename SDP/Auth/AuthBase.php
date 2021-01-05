<?php
namespace ND\SDP\Auth;

use Shisa\HTTPClient\Auth\AbstractAuth;

/**
 * 授权
 * 
 * @method \ND\SDP\SdpClient getClient()
 */
abstract class AuthBase extends AbstractAuth
{
    /**
     * Token更新事件
     */
    const EVENT_TOKENREFRESHED = 'refresh';
    
    private $eventHandlers = [];

    public function subscribe($event, $handler)
    {
        $this->eventHandlers[$event][] = $handler;
    }

    protected function fire($event, $sender, $message)
    {
        foreach($this->eventHandlers[$event] as $handler) {
            $handler($sender, $message);
        }
    }

    public static function createAuthorizationStr($strategy, $params)
    {
        $args = [];
        foreach($params as $key => $value) {
            $args[] = $key . '="' . $value . '"';
        }
        return implode(' ', [$strategy, implode(',', $args)]);
    }
}