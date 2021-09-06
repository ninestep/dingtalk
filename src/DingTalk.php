<?php


namespace Shenhou\Dingtalk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Cache\InvalidArgumentException;
use Shenhou\Dingtalk\topapi\Processinstance;

/**
 * Class DingTalk
 * @package Shenhou\Dingtalk
 */
class DingTalk
{
    protected static $config = [];

    /**
     * DingTalk constructor.
     * @param array $config
     *         $config = [
     * //自定义固定字符串。
     * 'nonceStr' => 'qwe123',
     * //应用的标识
     * 'agentId' => '1273361157',
     * //应用的标识
     * 'AppKey' => 'suitek4hnec7ytbszn3mw',
     * //如果是定制应用，输入定制应用的CustomKey
     * 'CustomKey'=>'',
     *              // 如果是第三方企业应用，输入第三方企业应用的SuiteKey
     *              'SuiteKey'=>'',
     * //如果是定制应用，输入定制应用的CustomSecret，
     * 'AppSecret' => '-6E-fanab1mCHRSh-78ThLTA7LX8uKtbKxqqD2E6hB0xzIwrI3qQLIs5c_uDT4HN',
     * 'CustomSecret'=>'',
     *              //如果是第三方企业应用，输入第三方企业应用的SuiteSecret
     *              'SuiteSecret'=>'',
     * //钉钉推送的suiteTicket。
     * 'suiteTicket'=>'',
     * //时间戳
     * 'timeStamp' => time(),
     * //企业id
     * 'corpId' => 'ding059b08b496f51f9235c2f4657eb6378f'
     *      ];
     */
    public function __construct($config = [])
    {
        if (empty($config)) {
            throw new DingTalkException('配置信息错误');
        }
        self::$config = $config;
    }

    /**
     * 获取配置
     * @param null|string $key 如果为空则返回全部配置否则返回对应配置内容
     * @return mixed|null
     * @throws DingTalkException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function config($key = null)
    {
        if ($key != null) {
            if (empty(self::$config[$key])) {
                return null;
            }
            return self::$config[$key];
        } else {
            return self::$config;
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
     * @throws DingTalkException
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    public static function getJsapiTicket()
    {
        if (Cache::has('jsapi_ticket')) {
            return Cache::get('jsapi_ticket');
        } else {
            try {
                $token = self::getAccessToken();
            } catch (GuzzleException $e) {
                throw new DingTalkException($e->getMessage());
            } catch (InvalidArgumentException $e) {
                throw new DingTalkException($e->getMessage());
            }
            //获取AccessToke
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
     * @throws \GuzzleHttp\Exception\GuzzleException|DingTalkException
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
                'json' => $data
            ]);
        $data = json_decode($res->getBody()->getContents(), true);
        if ($data['errcode'] == 0) {
            return isset($data['result']) ? $data['result'] : $data;
        } else {
            throw new DingTalkException($data['errmsg'], $data['errcode']);
        }
    }

    /**
     * 部门
     * @return Dept
     */
    public function dept()
    {
        return new Dept();
    }

    /**
     * 用户
     * @return User
     */
    public function user()
    {
        return new User();
    }

    /**
     * 角色
     * @return Role
     */
    public function role()
    {
        return new Role();
    }

    /**
     * js_api
     * @return JsApi
     */
    public function js_api()
    {
        return new JsApi(self::config('nonceStr'));
    }

    /**
     * 消息发送
     * @return Message
     */
    public function message()
    {
        return new Message();
    }

    /**
     * 回调
     * @param string $token 钉钉开放平台上，开发者设置的token
     * @param string $aesKey 钉钉开放台上，开发者设置的EncodingAESKey
     * @param string $ownerKey 企业自建应用-事件订阅, 使用appKey
     *                       企业自建应用-注册回调地址, 使用corpId
     *                       第三方企业应用, 使用suiteKey
     * @return CallBack
     */
    public function callback(string $token, string $aesKey, string $ownerKey)
    {
        return new CallBack($token, $aesKey, $ownerKey);
    }

    public function attendance(): Attendance
    {
        return new Attendance();
    }

    /**
     * 官方工作流
     * @return Processinstance
     */
    public function processinstance(): Processinstance
    {
        return new Processinstance();
    }
}