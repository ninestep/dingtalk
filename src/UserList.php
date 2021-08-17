<?php

namespace Shenhou\Dingtalk;

use app\model\UserLists;
use think\facade\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use app\model\DeptList;

class UserList
{
    /**
     * 获取部门列表
     * @param int $dept_id
     * @param string $language
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDeptList($dept_id = 1, $language = 'zh_CN')
    {
        $token = common::getAccessToken();//获取AccessToke
        $client = new Client();
        $res = $client->request('POST', 'https://oapi.dingtalk.com/topapi/v2/department/listsub', [
            'form_params' => [
                'access_token' => $token,
                'dept_id' => $dept_id,
                'language' => $language
            ]
        ]);
        $data = json_decode($res->getBody()->getContents(), true);
        $arr = $data['result'];
        $dtmList = array();
        foreach ($arr as $v) {
//            dump($v);
            $dtmList['name'] = $v['name'];
            $dtmList['parent_id'] = $v['parent_id'];
            $dtmList['dept_id'] = $v['dept_id'];
            $dtmList['createtime'] = time();
            $res = DeptList::where('dept_id', $v['dept_id'])->find();
            if ($res) {
                return json_encode($data, true);
            } else {
                $list = new DeptList();
                $list->save($dtmList);
            }
        }

    }

    /**
     * 获取部门详细
     * @param int $dept_id
     * @param string $language
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function DeptDetailed($dept_id = 1, $language = 'zh_CN')
    {
        $token = common::getAccessToken();
        $client = new Client();
        $res = $client->request('POST', 'https://oapi.dingtalk.com/topapi/v2/department/get', [
            'form_params' => [
                'access_token' => $token,
                'dept_id' => $dept_id,
                'language' => $language,
            ]
        ]);
        $data = json_decode($res->getBody()->getContents(), true);
        $arr = $data['result'];
        dump($arr);
    }

    /**
     * 获取用户的唯一id
     * @param int $dept_id
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function UserIdList($dept_id = 1)
    {
        $token = common::getAccessToken();
        $client = new Client();
        $res = $client->request('POST', 'https://oapi.dingtalk.com/topapi/user/listid', [
            'form_params' => [
                'access_token' => $token,
                'dept_id' => $dept_id
            ]
        ]);
        $data = json_decode($res->getBody()->getContents(), true);
        dump($data);
    }

    /**
     * 获取用户列表
     * @param int $dept_id
     * @param int $cursor
     * @param int $size
     * @return false|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     */
    public function UserList($dept_id = 321181063, $cursor = 0, $size = 10)
    {
        $token = common::getAccessToken();
        $client = new Client();
        $res = $client->request('POST', 'https://oapi.dingtalk.com/topapi/v2/user/list', [
            'form_params' => [
                'access_token' => $token,
                'dept_id' => $dept_id,
                'cursor' => $cursor,
                'size' => $size
            ]
        ]);
        $data = json_decode($res->getBody()->getContents(), true);
        $arr = $data['result']['list'];
        //dump($arr);
        $userlist = array();
        foreach ($arr as $v) {
            //dump($v);
            $userlist['mobile'] = $v['mobile'];
            $userlist['name'] = $v['name'];
            $userlist['state_code'] = $v['state_code'];
            $userlist['title'] = $v['title'];
            $userlist['unionid'] = $v['unionid'];
            $userlist['userid'] = $v['userid'];
            $userlist['createtime'] = time();
            $res = UserLists::where('userid', $v['userid'])->find();
            if ($res) {
                return json_encode($data, true);
            } else {
                echo "添加";
                $list = new UserLists();
                $list->save($userlist);
            }

        }
        return json_encode($data, true);
    }

}