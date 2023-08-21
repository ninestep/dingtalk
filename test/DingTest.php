<?php

namespace Shenhou\Tests;

use DateInterval;
use DateTime;
use Shenhou\Dingtalk\DingTalk;

require __DIR__ . '/../vendor/autoload.php';

class DingTest
{
    private $dingtalk;

    public function __construct()
    {
        $config = require 'config.php';
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
        return $attendance->list($dateFrom, $dateTo, []);
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
        $res = $task->add('测试', '6Lj0lHrjvF05sNEYO4Q3rAiEiE');
        print_r($res);
    }

    public function getSimpleGroups()
    {
        $res = $this->dingtalk->attendance()->getSimpleGroups(0);
        print_r($res);
    }

    public function groupsIdToKey()
    {
        $res = $this->dingtalk->attendance()->groupsIdToKey('804885206');
        print_r($res);
    }

    public function groupPositionsAdd()
    {
        $res = $this->dingtalk->attendance()->groupPositionsAdd(
            'B78369389BA1BFECCD6AC23FB8268CC5', [[
            "foreign_id" => "0151E23B1",
            "address" => "阿里巴巴西溪北苑",
            "latitude" => "30.123",
            "longitude" => "120.123",
            "offset" => 100
        ]]);
        print_r($res);
    }
}

$d = new DingTest();
//$d->processinstance();
//$d->task();
//$d->getSimpleGroups();
//$d->groupsIdToKey();
$d->groupPositionsAdd();