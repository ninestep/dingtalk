<?php

use Shenhou\Dingtalk\DingTalk;

include_once '../vendor/autoload.php';
$config = require 'config.php.example';

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
