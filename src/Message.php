<?php


namespace Shenhou\Dingtalk;


class Message
{
    /**
     * 发送工作通知
     * @param array $msg 消息内容，最长不超过2048个字节
     * @param array $userid_list 接收者的userid列表，最大用户列表长度100。
     * @param array $dept_id_list 接收者的部门id列表，最大列表长度20。
     * @param false $to_all_user 是否发送给企业全部用户。
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function corpconversationAsyncsendV2($msg, $userid_list = [], $dept_id_list = [], $to_all_user = false)
    {
        if (empty($userid_list) && empty($dept_id_list) && !$to_all_user) {
            throw new DingTalkException('发送对象不能为空');
        }
        $data = [
            'msg' => $msg,
            'agent_id' => common::config('agentId'),
        ];
        if (!empty($userid_list)){
            $data['userid_list'] = implode(',', $userid_list);
        }
        if (!empty($dept_id_list)){
            $data['dept_id_list'] = implode(',', $dept_id_list);
        }
        if ($to_all_user == true){
            $data['to_all_user'] = true;
        }
        $res = common::requestPost('/topapi/message/corpconversation/asyncsend_v2', $data);
        return $res;
    }
}