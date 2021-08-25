<?php


namespace Shenhou\Dingtalk\topapi;


use Shenhou\Dingtalk\common;
use Shenhou\Dingtalk\DingTalkException;

class Processinstance
{
    /**
     * 获取审批实例详情
     * @param string $process_instance_id 底层实例id
     * @return array
     * @throws DingTalkException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($process_instance_id)
    {
        $res = common::requestPost('/topapi/processinstance/get', [
            'process_instance_id' => $process_instance_id
        ]);
        return $res['process_instance'];
    }
}