<?php
namespace Shenhou\Dingtalk;


class JsApi{
    /**
     * 计算dd.config的签名参数
     **/
    public function DdConfigSign($url, $timestamp)
    {
        $config = common::config();
        $plain = 'jsapi_ticket=' . common::getJsapiTicket() .
            '&noncestr=' . $config['nonceStr'] .
            '&timestamp=' . $timestamp .
            '&url=' . $url;
        return sha1($plain);

    }
}
