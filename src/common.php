<?php

namespace Shenhou\Dingtalk;
// 应用公共文件
//该公共方法获取和全局缓存js-sdk需要使用的access_token
use GuzzleHttp\Client;

class common extends DingTalk
{
    /**
     * 获取配置
     * @param null|string $key 如果为空则返回全部配置否则返回对应配置内容
     * @return mixed|null
     * @throws DingTalkException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function config($key = null)
    {
        if (Cache::has('config')) {
            $config = Cache::get('config');
        } else {
            throw new DingTalkException('配置信息为空');
        }
        if ($key != null) {
            if (empty($config[$key])) {
                return null;
            }
            return $config[$key];
        } else {
            return $config;
        }
    }

    /**
     * 获取AccessToken缓存接口
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getAccessToken()
    {
        //开发类型。self企业内部应用开发，customize服务商定制应用,suite第三方定制
        $type = 'self';
        $url = 'https://oapi.dingtalk.com/gettoken';
        $appkey = self::config('AppKey');
        if (empty($appkey)) {
            $appkey = self::config('CustomKey');
            $type = 'customize';
            $url = 'https://oapi.dingtalk.com/service/get_corp_token';
        }
        if (empty($appkey)) {
            $appkey = self::config('SuiteKey');
            $type = 'suite';
            $url = 'https://oapi.dingtalk.com/service/get_suite_token';
        }
        if (empty($appkey)) {
            throw new DingTalkException('配置获取错误');
        }

        $cacheKey = 'access_token_' . $type . '_' . $appkey;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        } else {
            switch ($type) {
                case 'self':
                    $AppSecret = self::config('AppSecret');
                    break;
                case 'customize':
                    $AppSecret = self::config('CustomSecret');
                    break;
                case 'suite':
                    $AppSecret = self::config('SuiteSecret');
                    break;
                default:
                    throw new DingTalkException('配置获取错误');
            }
            $set = ['appkey' => $appkey, 'appsecret' => $AppSecret];
            if ($type == 'customize') {
                $set = [
                    'accessKey' => $appkey,
                    'accessSecret' => $AppSecret,
                    'suiteTicket' => self::config('suiteTicket'),
                    'auth_corpid' => self::config('corpId'),
                ];
            }
            if ($type == 'suite') {
                $set = [
                    'suite_key' => $appkey,
                    'suite_secret' => $AppSecret,
                    'suite_ticket' => self::config('suiteTicket')
                ];
            }
            $client = new Client();
            if ($type == 'self') {
                $response = $client->request('GET',
                    $url,
                    ['query' => $set]
                );
            } else {
                $response = $client->request('POST',
                    $url,
                    ['query' => $set]
                );
            }
            $res = json_decode($response->getBody()->getContents());
            switch ($type) {
                case 'self':
                case 'customize':
                    Cache::set($cacheKey, $res->access_token, 7000);
                    return $res->access_token;
                case 'suite':
                    Cache::set($cacheKey, $res->suite_access_token, 7000);
                    return $res->suite_access_token;
            }
            return  '';
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
            return isset($data['result']) ? $data['result'] : $data;
        } else {
            throw new DingTalkException($data['errmsg'], $data['errcode']);
        }
    }
}


