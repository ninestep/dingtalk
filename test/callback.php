<?php

use Shenhou\Dingtalk\DingTalk;

include_once '../vendor/autoload.php';
$config = [
    //自定义固定字符串。
    'nonceStr' => 'qwe123',
    //应用的标识
    'agentId' => '1273361157',
    //应用的标识
    'AppKey' => '',
    //如果是定制应用，输入定制应用的CustomKey
    'CustomKey' => 'suitek4hnec7ytbszn3mw',
    //如果是定制应用，输入定制应用的CustomSecret，
    'AppSecret' => '',
    'CustomSecret' => '-6E-fanab1mCHRSh-78ThLTA7LX8uKtbKxqqD2E6hB0xzIwrI3qQLIs5c_uDT4HN',
    //钉钉推送的suiteTicket。
    'suiteTicket' => 'sadasdasdasdasda',
    //时间戳
    'timeStamp' => time(),
    //企业id
    'corpId' => 'ding059b08b496f51f9235c2f4657eb6378f'
];
try {
    $dingtalk = new DingTalk($config);
} catch (\Shenhou\Dingtalk\DingTalkException $e) {
    echo $e->getMessage();
}
$get = $_GET['msg_signature'];
$post = $_POST['encrypt'];
echo 1111;