<?php
namespace Shenhou\Dingtalk;


class JsApi{
    private $nonceStr = '';
    public function __construct($nonceStr)
    {
        $this->nonceStr = $nonceStr;
    }

    /**
     * 计算dd.config的签名参数
     **/
    public function DdConfigSign($url, $timestamp)
    {
        $plain = 'jsapi_ticket=' . DingTalk::getJsapiTicket() .
            '&noncestr=' . $this->nonceStr .
            '&timestamp=' . $timestamp .
            '&url=' . $url;
        return sha1($plain);

    }
}
