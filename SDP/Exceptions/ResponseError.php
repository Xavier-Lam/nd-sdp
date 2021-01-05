<?php
namespace ND\SDP\Exceptions;

/**
 * 常规响应异常
 */
class ResponseError extends SDPException
{
    public $errorCode;

    public $requestId;

    public $hostId;

    public $serverTime;

    public function __construct($code, $message, $requestId, $hostId, $serverTime, $statusCode) {
        parent::__construct($message, $statusCode);

        $this->errorCode = $code;
        $this->requestId = $requestId;
        $this->hostId = $hostId;
        $this->serverTime = new \DateTime($serverTime);
    }
}