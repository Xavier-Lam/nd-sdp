<?php
namespace ND\SDP\Client;

use Shisa\HTTPClient\Clients\RecursiveClientMixin;
use Shisa\HTTPClient\Formatters\JsonFormatter;
use Shisa\HTTPClient\HTTP\Request;
use Shisa\HTTPClient\HTTP\Response;
use ND\SDP\Exceptions\ResponseError;
use Shisa\HTTPClient\Clients\HTTPClient;
use Shisa\HTTPClient\Exceptions\HTTPError;

class BaseClient extends HTTPClient
{
    use RecursiveClientMixin;
    
    public function __construct()
    {
        $this->setFormatter(new JsonFormatter());
    }

    public function getBaseUrl()
    {
        $env = $this->getBaseClient()->getEnv();
        if($env) {
            try {
                $baseUrl = constant(get_class($this) . '::ENV_' . $env);
            }
            catch(\Error $e) {

            }
        }
        return $baseUrl?: parent::getBaseUrl();
    }

    protected function handleResponse(Response $response, Request $request, $options = [])
    {
        $noexceptions = $options[static::OPTION_RESPONSENOEXCEPTIONS];
        $options[static::OPTION_RESPONSENOEXCEPTIONS] = true;
        $response = parent::handleResponse($response, $request, $options);

        if(!$noexceptions && !$response->isSuccess()) {
            $json = $response->json();
            if(isset($json['code'])) {
                throw new ResponseError(
                    $json['code'],
                    $json['message'],
                    $json['request_id'],
                    $json['host_id'],
                    $json['server_time'],
                    $response->statusCode);
            } else {
                throw new HTTPError($response);
            }
        }

        return $response;
    }
}