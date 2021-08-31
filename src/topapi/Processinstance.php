<?php


namespace Shenhou\Dingtalk\topapi;


use Shenhou\Dingtalk\common;
use Shenhou\Dingtalk\DingTalkException;

class Processinstance
{
    /**
     * 获取审批实例ID列表
     * @param string $process_code 审批流的唯一码。process_code在审批模板编辑页面的URL中获取。
     * @param int $start_time 审批实例开始时间。Unix时间戳，单位毫秒。
     * @param int $end_time 审批实例结束时间，Unix时间戳，单位毫秒。
     * @param int $size 分页参数，每页大小，最多传20。
     * @param int $cursor 分页查询的游标，最开始传0，后续传返回参数中的next_cursor值。
     * @param array $userid_list 发起userid列表，最大列表长度为10。
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listids($process_code, $start_time, $end_time, $size = 20, $cursor = 0, $userid_list = [])
    {
        if (count($userid_list)>10){
            throw new DingTalkException('起userid列表，最大列表长度为10');
        }
        return common::requestPost('/topapi/processinstance/listids', [
            'process_code' => $process_code,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'size' => $size,
            'cursor' => $cursor,
            'userid_list' => implode(',', $userid_list)
        ]);
    }

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