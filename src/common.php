<?php

namespace Shenhou\Dingtalk;
// 应用公共文件
//该公共方法获取和全局缓存js-sdk需要使用的access_token
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Cache\InvalidArgumentException;

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
     * @throws DingTalkException
     * @throws GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
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
                $timeStamp = self::config('timeStamp') * 1000;
                $suiteTicket = self::config('suiteTicket');
                $key = self::config('CustomSecret');
                $signature = $timeStamp . "\n" . $suiteTicket;
                $signature = hash_hmac('sha256', $signature,
                    $key, true);
                $signature = base64_encode($signature);
                $url .= '?signature=' . $signature .
                    '&timestamp=' . $timeStamp .
                    '&suiteTicket=' . $suiteTicket .
                    '&accessKey=' . $appkey;
                $set = [
                    'accessKey' => $appkey,
                    'timestamp' => $timeStamp,
                    'signature' => $signature,
                    'suiteTicket' => $suiteTicket,
                    'auth_corpid' => self::config('corpId'),
                ];
            }
            if ($type == 'suite') {
                $timeStamp = self::config('timeStamp');
                $suiteTicket = self::config('suiteTicket');
                $key = self::config('SuiteSecret');
                $signature = $timeStamp . "\n" . $suiteTicket;
                $signature = hash_hmac('sha256', $signature,
                    $key, true);
                $signature = base64_encode($signature);
                $url .= '?signature=' . $signature .
                    '&timestamp=' . $timeStamp .
                    '&suiteTicket=' . $suiteTicket .
                    '&accessKey=' . $key;
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
                    [
                        'headers' => ['Content-Type' => 'application/json'],
                        'json' => $set
                    ]
                );
            }
            $res = json_decode($response->getBody()->getContents());
            if ($res->errcode == 0) {
                switch ($type) {
                    case 'self':
                    case 'customize':
                        Cache::set($cacheKey, $res->access_token, 7000);
                        return $res->access_token;
                    case 'suite':
                        Cache::set($cacheKey, $res->suite_access_token, 7000);
                        return $res->suite_access_token;
                }
            } else {
                throw new DingTalkException($res->errmsg);
            }
            return '';
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
        // todo : 此处需要增加密钥失效后重新申请密钥的功能
        $client = new Client([
            'base_uri' => 'https://oapi.dingtalk.com',
            'timeout' => 30,
            'allow_redirects' => false,
        ]);
        try {
            $data['access_token'] = self::getAccessToken();
        } catch (GuzzleException $e) {
            throw new DingTalkException($e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw new DingTalkException($e->getMessage());
        } catch (DingTalkException $e) {
            throw new DingTalkException($e->getMessage());
        }
        $uri .= '?access_token=' . $data['access_token'];
        $res = $client->request('POST', $uri,
            [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => array_filter($data)
            ]);
        $data = json_decode($res->getBody()->getContents(), true);
        if ($data['errcode'] == 0) {
            return isset($data['result']) ? $data['result'] : $data;
        } else {
            throw new DingTalkException($data['errmsg'], $data['errcode']);
        }
    }
}


