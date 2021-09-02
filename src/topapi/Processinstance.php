<?php


namespace Shenhou\Dingtalk\topapi;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
    public function listids($process_code, $start_time, $end_time, $size = 20, $cursor = 0, $userid_list = []): array
    {
        if (count($userid_list) > 10) {
            throw new DingTalkException('发起userid列表，最大列表长度为10');
        }
        $data = [
            'process_code' => $process_code,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'size' => $size,
            'cursor' => $cursor,
        ];
        if (!empty($userid_list)) {
            $data['userid_list'] = implode(',', $userid_list);
        }
        return common::requestPost('/topapi/processinstance/listids', $data);
    }

    /**
     * 获取审批实例详情
     * @param string $process_instance_id 底层实例id
     * @return array
     * @throws DingTalkException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($process_instance_id): array
    {
        $res = common::requestPost('/topapi/processinstance/get', [
            'process_instance_id' => $process_instance_id
        ]);
        return $res['process_instance'];
    }

    /**
     * 获取审批附件
     * @param string $process_instance_id 审批单实例id
     * @param string $file_id 文件id
     * @param string $path 文件保存位置,如果不提供，直接返回下载地址
     * @return string 文件保存位置
     * @throws DingTalkException
     * @throws GuzzleException
     */
    public function file_url_get($process_instance_id, $file_id, $path = null): string
    {
        $res = common::requestPost('/topapi/processinstance/file/url/get', [
            'request' => [
                'process_instance_id' => $process_instance_id,
                'file_id' => $file_id,
            ]
        ]);
        $uri = $res['download_uri'];
        if (empty($path)) {
            return $uri;
        }
        try {
            $client = new Client();
            $response = $client->request('get', $uri, ['sink' => $path]);
            if ($response->getStatusCode() == 200) {
                return $path;
            } else {
                throw new DingTalkException('下载失败');
            }
        } catch (GuzzleException $e) {
            throw new DingTalkException($e->getMessage());
        }
    }
}