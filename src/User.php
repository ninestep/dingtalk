<?php


namespace Shenhou\Dingtalk;


use GuzzleHttp\Client;
use think\Exception;

class User
{

    /**
     * 用免登code换取用户信息
     * @param string $code 钉钉免登code
     * @return array 用户信息
     * @throws Exception
     */
    public function getUserInfo(string $code): array
    {
        return common::requestPost('/topapi/v2/user/getuserinfo', [
            'code' => $code
        ]);
    }

    /**
     * 获取部门用户详情
     * @param int $dept_id 部门id
     * @param int $cursor 游标
     * @param int $size 煤业大小
     * @return array
     * @throws Exception
     */
    public function list(int $dept_id = 1, int $cursor = 0, int $size = 10): array
    {
        return common::requestPost('/topapi/v2/user/list', [
            'dept_id' => $dept_id,
            'cursor' => $cursor,
            'size' => $size
        ]);
    }

    /**
     * 获取部门用户userid列表
     * @param int $dept_id 部门id
     * @return array
     * @throws Exception
     */
    public function listid(int $dept_id = 1): array
    {
        return common::requestPost('/topapi/user/listid', [
            'dept_id' => $dept_id
        ]);
    }

    /**
     * 根据userid获取用户详情
     * @param string $userid 用户id
     * @param string $language 通讯录语言。zh_CN：中文（默认值）en_US：英文
     * @return array
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $userid, string $language = 'zh_CN'): array
    {
        return common::requestPost('/topapi/v2/user/get', [
            'userid' => $userid,
            'language' => $language
        ]);
    }


}