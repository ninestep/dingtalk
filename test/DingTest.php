<?php


class DingTest
{
    private $dingtalk;

    public function __construct()
    {
        $config = [
            //自定义固定字符串。
            'nonceStr' => 'qwe123',
            //应用的标识
            'agentId' => '1174082692',
            //应用的标识
            'AppKey' => 'dingy7qj9muiygzmp4zi',
            'AppSecret' => '-TiZ64lOceMUQ-stbDZg3g0vzyw81lCA8QklIP224TbDicCEZQ8SMQcVu1gMY57O',
            //时间戳
            'timeStamp' => time(),
            //企业id
            'corpId' => 'ding2757e0a18a1c1b64ee0f45d8e4f7c288'
        ];
        $this->dingtalk = new \Shenhou\Dingtalk\DingTalk($config);
    }
}