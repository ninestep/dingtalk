<?php
namespace Shenhou\Tests;

use DateInterval;
use DateTime;
use Shenhou\Dingtalk\DingTalk;

require __DIR__.'/../vendor/autoload.php';
class DingTest
{
    private $dingtalk;

    public function __construct()
    {
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
            $this->dingtalk = new DingTalk($config);
        } catch (\Shenhou\Dingtalk\DingTalkException $e) {
        }
    }

    public function register()
    {
        $callback = $this->dingtalk->callback('dingtalk',
            'hvnzd2y8jkhx8yoo4483xxxx123456789asdfghjkli',
            'ding059b08b496f51f9235c2f4657eb6378f');
        $res = $callback->register([
            'attendance_check_record'
        ], 'http://ybdd.vaiwan.com/callback');
        print_r($res);
    }

    public function attendance()
    {
        $attendance = $this->dingtalk->attendance();
        $objDateTime = new DateTime('NOW');
        $dateTo = $objDateTime->format('Y-m-d H:i:s');
        $dateFrom = $objDateTime
            ->sub(new DateInterval('P7D'))
            ->format('Y-m-d H:i:s');
        return $attendance->list($dateFrom,$dateTo,[]);
    }
}

$d = new DingTest();
$d->register();