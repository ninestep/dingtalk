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
     *          //自定义固定字符串。
     *          'nonceStr' => 'qwe123',
     *          //应用的标识
     *          'agentId' => '1174082692',
     *          //应用的标识
     *          'AppKey' => 'dingy7qj9muiygzmp4zi',
     *          'AppSecret' => '-TiZ64lOceMUQ-stbDZg3g0vzyw81lCA8QklIP224TbDicCEZQ8SMQcVu1gMY57O',
     *          //时间戳
     *          'timeStamp' => time(),
     *          //企业id
     *          'corpId' => 'ding2757e0a18a1c1b64ee0f45d8e4f7c288'
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