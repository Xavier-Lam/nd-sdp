<?php
namespace ND\SDP\Clients\Wallet;

/**
 * 积分商户
 */
class WalletBusiness
{
    public $key;

    public $business;

    public function __construct($business, $key)
    {
        $this->business = $business;
        $this->key = $key;
    }
}