<?php


namespace Shenhou\Dingtalk;

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
                    //自定义固定字符串。
                    'nonceStr' => 'qwe123',
                    //应用的标识
                    'agentId' => '1273361157',
                    //应用的标识
                    'AppKey' => 'suitek4hnec7ytbszn3mw',
                    //如果是定制应用，输入定制应用的CustomKey
                    'CustomKey'=>'',
     *              // 如果是第三方企业应用，输入第三方企业应用的SuiteKey
     *              'SuiteKey'=>'',
                    //如果是定制应用，输入定制应用的CustomSecret，
                    'AppSecret' => '-6E-fanab1mCHRSh-78ThLTA7LX8uKtbKxqqD2E6hB0xzIwrI3qQLIs5c_uDT4HN',
                    'CustomSecret'=>'',
     *              //如果是第三方企业应用，输入第三方企业应用的SuiteSecret
     *              'SuiteSecret'=>'',
                    //钉钉推送的suiteTicket。
                    'suiteTicket'=>'',
                    //时间戳
                    'timeStamp' => time(),
                    //企业id
                    'corpId' => 'ding059b08b496f51f9235c2f4657eb6378f'
     *      ];
     */
    public function __construct($config = [])
    {
        if (empty($config)) {
            $config = Cache::get('config');
        } else {
            Cache::set('config', $config);
        }
        if (empty($config)) {
            throw new DingTalkException('配置信息错误');
        }
        self::$config = $config;
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
        return new JsApi();
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
}