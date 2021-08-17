<?php

namespace Shenhou\Dingtalk;
// 应用公共文件
//该公共方法获取和全局缓存js-sdk需要使用的access_token
use GuzzleHttp\Client;

class common extends DingTalk
{
    public static function config($key = null)
    {
        if ($key != null) {
            return self::$config[$key];
        } else {
            return self::$config;
        }
    }

    /**
     * 获取AccessToken缓存接口
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getAccessToken()
    {
        $appkey = self::config('AppKey');
        $cacheKey = 'access_token_' . $appkey;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        } else {
            $AppSecret = self::config('AppSecret');
            $client = new Client();
            $response = $client->request('GET',
                'https://oapi.dingtalk.com/gettoken',
                ['query' => ['appkey' => $appkey, 'appsecret' => $AppSecret]]
            );
            $res = json_decode($response->getBody()->getContents());
            Cache::set($cacheKey, $res->access_token, 7000);
            return $res->access_token;
        }
    }

    /**
     * 获取jsapi_ticket
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getJsapiTicket()
    {
        if (Cache::has('jsapi_ticket')) {
            return Cache::get('jsapi_ticket');
        } else {
            $token = common::getAccessToken();//获取AccessToke
            $client = new Client();
            $response = $client->request('GET',
                'https://oapi.dingtalk.com/get_jsapi_ticket',
                ['query' => ['access_token' => $token]]
            );
            $res = json_decode($response->getBody()->getContents(), true);
            if ($res['errcode'] != 0) {
                throw new DingTalkException($res['errmsg'], $res['errcode']);
            }
            $jsck = $res['ticket'];
            Cache::set('jsapi_ticket', $jsck, 7000);
            return $jsck;
        }

    }

    /**
     * post请求
     * @param string $uri 地址
     * @param array $data 请求参数
     * @return array 返回值
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function requestPost($uri, $data)
    {
        $client = new Client([
            'base_uri' => 'https://oapi.dingtalk.com',
            'timeout' => 30,
            'allow_redirects' => false,
        ]);
        $data['access_token'] = self::getAccessToken();
        $res = $client->request('POST', $uri, [
            'form_params' => $data
        ]);
        $data = json_decode($res->getBody()->getContents(), true);
        if ($data['errcode'] == 0) {
            return empty($data['result']) ? $data : $data['result'];
        } else {
            throw new DingTalkException($data['errmsg'], $data['errcode']);
        }
    }
}


