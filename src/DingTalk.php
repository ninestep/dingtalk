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
        if (empty($config)){
            $config = Cache::get('config');
        }else{
            Cache::set('config',$config);
        }
        if (empty($config)){
            throw new DingTalkException('配置信息错误');
        }
        self::$config = $config;
    }

    public function dept()
    {
        return new Dept();
    }
}