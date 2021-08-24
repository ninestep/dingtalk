#dingtalk

本仓库为神州中联信息技术有限公司针对钉钉接口开发的对接包。

## 1. 使用方法
1. 使用命令行`composer require shenzhou/dingtalk`安装。
2. 实例化
~~~ php
$config = [
    //自定义固定字符串。
    'nonceStr' => 'qwe123',
    //应用的标识
    'agentId' => '1273361157',
    //应用的标识
    'AppKey' => '',
    //如果是定制应用，输入定制应用的CustomKey
    'CustomKey' => '11111',
    //如果是定制应用，输入定制应用的CustomSecret，
    'AppSecret' => '',
    'CustomSecret' => '-6E-111111-78ThLTA7LX8uKtbKxqqD2E6hB0xzIwrI3qQLIs5c_uDT4HN',
    //钉钉推送的suiteTicket。
    'suiteTicket' => 'sadasdasd111asdasda',
    //时间戳
    'timeStamp' => time(),
    //企业id
    'corpId' => 'ding01111159b08b496f51f9235c2f4657eb6378f'
]
$this->dingtalk = new DingTalk($config);

~~~

3. 使用各模块
`$attendance = $this->dingtalk->attendance();`
   
> 模块寻找方式
> 例如考勤获取接口为`https://oapi.dingtalk.com/attendance/list`
> 则模块名为`attendance`,函数名为`list`

## 2. 已实现模块

