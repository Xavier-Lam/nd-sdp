<?php
namespace ND\SDP\Clients\Wallet;

use ND\SDP\Client\TenantClient;

/**
 * 养成币服务端
 * http://wiki.doc.101.com/index.php?title=养成币服务端
 */
class PointClient extends TenantClient
{
    /**
     * 养成币种查询
     * http://wiki.doc.101.com/index.php?title=养成币服务端#.E3.80.90GET.E3.80.91_.2Fv0.1.2Fwallet.2Fc.2Fpoints.2Fcurrencies_.E5.85.BB.E6.88.90.E5.B8.81.E7.A7.8D.E6.9F.A5.E8.AF.A2
     */
    public function currencies()
    {
        return $this->sendWithAuth('/v0.1/wallet/c/points/currencies')
            ->json();
    }

    /**
     * 交易-养成币增加或扣减
     * http://wiki.doc.101.com/index.php?title=养成币服务端#.5BPOST.5D.2Fv0.1.2Fwallet.2Fs.2Fpoints_.E4.BA.A4.E6.98.93-.E5.85.BB.E6.88.90.E5.B8.81.E5.A2.9E.E5.8A.A0.E6.88.96.E6.89.A3.E5.87.8F
     */
    public function change(WalletBusiness $biz, $uid, $currency, $amount, $businessBizId, $remark = '') {
        // TODO: status_code === 'POINTS/SUCCESS' 方为成功
        return $this->sendWithSign(
            $biz,
            '/v0.1/wallet/s/points',
            'POST',
            [
                'uid' => $uid,
                'currency' => $currency,
                'business_bizid' => $businessBizId,
                'amount' => $amount,
                'remark' => $remark
            ],
            ['uid', 'business', 'business_bizid', 'currency', 'amount']
        )->json();
    }

    /**
     * 交易-养成币余额查询
     * http://wiki.doc.101.com/index.php?title=养成币服务端#.5BPOST.5D.2Fv0.1.2Fwallet.2Fs.2Fpoints_.E4.BA.A4.E6.98.93-.E5.85.BB.E6.88.90.E5.B8.81.E5.A2.9E.E5.8A.A0.E6.88.96.E6.89.A3.E5.87.8F
     */
    public function balance(WalletBusiness $biz, $uid, $currency)
    {
        return $this->sendWithSign(
            $biz,
            '/v0.1/wallet/s/points',
            'GET',
            [
                'uid' => $uid,
                'currency' => $currency
            ],
            ['business', 'uid', 'currency']
        )->json();
    }

    public function sendWithSign(
        WalletBusiness $biz,
        $url,
        $method = 'GET',
        $data = [],
        $sign = [],
        $params = [],
        $headers = [],
        $options = [])
    {
        $options = array_merge($options, ['sign' => $sign, 'biz' => $biz]);
        return $this->sendWithAuth($url, $data, $method, $params, $headers, $options);
    }

    public function prepare($request, $options = [])
    {
        $preparedRequest = parent::prepare($request, $options);

        // 对请求进行签名
        if(array_key_exists('sign', $options) && $options['sign']) {
            $business = $options['biz']->business;
            $secretkey = $options['biz']->key;

            $strToSign = '';
            foreach($options['sign'] as $key) {
                $strToSign .= $key == 'business'? $business: $request->data[$key];
            }
            $sign = md5($strToSign . $secretkey);

            $preparedRequest->headers[] = "Point-Authentication: business=$business,sign=$sign";
        }

        return $preparedRequest;
    }
}