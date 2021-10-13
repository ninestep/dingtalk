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

|所属模块|功能|备注|
|----|-----|-----|
|智能工作流|获取审批实例ID列表||
|智能工作流|获取审批实例详情||
|智能工作流|获取审批附件||
|考勤|注册回调事件||
|考勤|获取打卡结果||
|考勤|获取打卡详情||
|回调|加密回调信息||
|回调|解密回调信息||
|通讯录-员工|用免登code换取用户信息||
|通讯录-员工|根据userid获取用户详情||
|通讯录-员工|员工增加||
|通讯录-员工|员工更新||
|通讯录-员工|根据手机号获取userid||
|通讯录-部门|获取部门列表||
|通讯录-部门|获取部门用户详情||
|通讯录-部门|获取部门用户userid列表||
|通讯录-部门|部门增加||
|通讯录-部门|获取部门详情||
|消息|发送工作通知||
|角色|获取指定角色的员工列表||
|角色|获取角色列表||
|js-api|计算dd.config的签名参数||
|免登|获取微应用后台免登的access_token||
|免登|获取应用管理员的身份信息||
