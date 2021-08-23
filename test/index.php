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
$msg_signature = $_GET['msg_signature'];
$nonce = $_GET['nonce'];
$signature = $_GET['signature'];
$timestamp = $_GET['timestamp'];
$post = json_decode(file_get_contents('php://input'), true);
$callback = $dingtalk->callback('dingtalk',
    'hvnzd2y8jkhx8yoo4483xxxx123456789asdfghjkli',
    'suitek4hnec7ytbszn3mw');
try {
    $msg = $callback->decrypt($msg_signature, $nonce, $post['encrypt'], $timestamp);
} catch (Exception $e) {
    echo $e->getMessage();
}
switch ($msg['EventType']) {
    case 'check_url':
        echo $callback->encrypt('success', time(), $nonce);
}
