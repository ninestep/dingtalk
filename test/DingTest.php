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
            'agentId' => '1304188113',
            //应用的标识
            'AppKey' => '',
            //如果是定制应用，输入定制应用的CustomKey
            'CustomKey' => 'suite3vbszlfhno9qctpd',
            //如果是定制应用，输入定制应用的CustomSecret，
            'AppSecret' => '',
            'CustomSecret' => 'hgXwIXil8g-T-xWU2PKvECD5yfZZc2rQ_tLAoCS-w0o_r57uplBvRG94zbR484zQ',
            //钉钉推送的suiteTicket。
            'suiteTicket' => 'aaaaqq111111132333',
            //时间戳
            'timeStamp' => time(),
            //企业id
            'corpId' => 'ding1ab2621028664f64a1320dcb25e91351',
            //回调参数
            'token' => 'xVZF1jX193QL',
            //回调参数
            'aesKey' => '7NEkXQd4HtaGELH5LAMBS2QudPwWeFKWudn3nL6QxGA',

            //回调中处理的流程号
            'processCode' => ['PROC-AC7264FA-06D2-432A-9923-F904126CD3D1'],
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

    public function processinstance()
    {
        $process = $this->dingtalk->processinstance();
        $info = $process->get('df00592d-ad1a-4678-99ec-f231f37a1e85');
        print_r($info);
    }

    public function task()
    {
        $task = $this->dingtalk->task();
        $res = $task->add('测试','6Lj0lHrjvF05sNEYO4Q3rAiEiE');
        print_r($res);
    }
}

$d = new DingTest();
//$d->processinstance();
$d->task();