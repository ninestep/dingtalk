<?php


namespace Shenhou\Dingtalk;


class Sso
{
    /**
     * 获取应用管理员的身份信息
     * @param string $code 通过Oauth认证给URL带上的code。
     * @return array
     * @throws DingTalkException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getuserinfo($code)
    {
        $access_token = $this->gettoken();
        return DingTalk::requestPost('/sso/getuserinfo', [
            'code' => $code,
            'access_token' => $access_token
        ]);
    }
    
    /**
     * 获取微应用后台免登的access_token
     * @return string
     * @throws DingTalkException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function gettoken(): string
    {
        $corpid = DingTalk::config('corpId');
        $corpsecret = DingTalk::config('corpsecret');
        $res = DingTalk::requestPost('/sso/gettoken', [
            'corpid' => $corpid,
            'corpsecret' => $corpsecret
        ]);
        return $res['access_token'];
    }
}