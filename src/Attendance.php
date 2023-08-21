<?php


namespace Shenhou\Dingtalk;


use GuzzleHttp\Exception\GuzzleException;

class Attendance
{
    /**
     * 获取打卡结果
     * @param string $workDateFrom 查询考勤打卡记录的起始工作日。格式为“yyyy-MM-dd HH:mm:ss”，HH:mm:ss可以使用00:00:00，将返回此日期从0点到24点的结果
     * @param string $workDateTo 查询考勤打卡记录的结束工作日。格式为“yyyy-MM-dd HH:mm:ss”，HH:mm:ss可以使用00:00:00，将返回此日期从0点到24点的结果。
     * @param array $userIdList 员工在企业内的userid列表，最多不能超过50个。
     * @param int $offset 表示获取考勤数据的起始点。第一次传0，如果还有多余数据，下次获取传的offset值为之前的offset+limit，0、1、2...依次递增。
     * @param int $limit 表示获取考勤数据的条数，最大不能超过50条。
     * @param false $isI18n 是否为海外企业使用： true：海外平台使用 false（默认）：国内平台使用
     * workDateFrom ≤ x ≤ workDateEnd，即起始与结束工作日最多相隔7天（包含7天）
     * @throws DingTalkException|\GuzzleHttp\Exception\GuzzleException
     * @return array ['list'考勤列表,hasMore,是否还有更多]
     */
    public function list(string $workDateFrom, string $workDateTo, array $userIdList, int $offset = 0, int $limit = 50, bool $isI18n = false)
    {
        $timeStampFrom = strtotime($workDateFrom);
        $timeStampTo = strtotime($workDateTo);
        if ($timeStampFrom >= $timeStampTo) {
            throw new DingTalkException('开始时间需要小于结束时间');
        }
        if ($timeStampTo - $timeStampFrom > 60 * 60 * 24 * 7) {
            throw new DingTalkException('起始与结束工作日最多相隔7天（包含7天）');
        }
        if (count($userIdList) > 50) {
            throw new DingTalkException('员工在企业内的userid列表，最多不能超过50个。');
        }
        $res = DingTalk::requestPost('/attendance/list', [
            'workDateFrom' => $workDateFrom,
            'workDateTo' => $workDateTo,
            'userIdList' => $userIdList,
            'offset' => $offset,
            'limit' => $limit,
            'isI18n' => $isI18n
        ]);
        return [
            'list' => $res['recordresult'],
            'hasMore' => $res['hasMore']
        ];
    }
/**
     * 获取打卡详情
     * @param string $workDateFrom 查询考勤打卡记录的起始工作日。格式为“yyyy-MM-dd HH:mm:ss”，HH:mm:ss可以使用00:00:00，将返回此日期从0点到24点的结果
     * @param string $workDateTo 查询考勤打卡记录的结束工作日。格式为“yyyy-MM-dd HH:mm:ss”，HH:mm:ss可以使用00:00:00，将返回此日期从0点到24点的结果。
     * @param array $userIdList 员工在企业内的userid列表，最多不能超过50个。
     * @param false $isI18n 是否为海外企业使用： true：海外平台使用 false（默认）：国内平台使用
     * workDateFrom ≤ x ≤ workDateEnd，即起始与结束工作日最多相隔7天（包含7天）
     * @throws DingTalkException|\GuzzleHttp\Exception\GuzzleException
     * @return array ['list'考勤列表,hasMore,是否还有更多]
     */
    public function listRecord(string $workDateFrom, string $workDateTo, array $userIdList, bool $isI18n = false)
    {
        $timeStampFrom = strtotime($workDateFrom);
        $timeStampTo = strtotime($workDateTo);
        if ($timeStampFrom >= $timeStampTo) {
            throw new DingTalkException('开始时间需要小于结束时间');
        }
        if ($timeStampTo - $timeStampFrom > 60 * 60 * 24 * 7) {
            throw new DingTalkException('起始与结束工作日最多相隔7天（包含7天）');
        }
        if (count($userIdList) > 50) {
            throw new DingTalkException('员工在企业内的userid列表，最多不能超过50个。');
        }
        $res = DingTalk::requestPost('/attendance/listRecord', [
            'checkDateFrom' => $workDateFrom,
            'checkDateTo' => $workDateTo,
            'userIds' => $userIdList,
            'isI18n' => $isI18n
        ]);
        return $res['recordresult'];
    }

    /**
     * 批量获取考勤组详情
     * @param int $offset 支持分页查询，与size参数同时设置时才生效，该参数代表偏移量，偏移量从0开始，下次调用传上次调用时的size与offset之和。
     * @param int $size 支持分页查询，与size参数同时设置时才生效，该参数代表偏移量，偏移量从0开始，下次调用传上次调用时的size与offset之和。
     * @return array 考勤组。
     * @throws DingTalkException
     * @throws GuzzleException
     */
    public function getSimpleGroups(int $offset,int $size=10)
    {

        $res = DingTalk::requestPost('/topapi/attendance/getsimplegroups', [
            'offset' => $offset,
            'size' => $size,
        ]);
        return $res;
    }

    /**
     * groupId转换为groupKey
     * @param string $groupId 考勤组ID。
     * @param string $opUserId 操作人的userId。
     * @return string 考勤组ID。
     * @throws DingTalkException
     */
    public function groupsIdToKey(string $groupId,string $opUserId='')
    {

        $res = DingTalk::requestPost('/topapi/attendance/groups/idtokey', [
            'op_user_id' => $opUserId,
            'group_id' => $groupId,
        ]);
        return $res;
    }
    /**
     * groupKey转换为groupId
     * @param string $groupId 考勤组ID。
     * @param string $opUserId 操作人的userId。
     * @return string 考勤组ID。
     * @throws DingTalkException
     */
    public function groupsKeyToKId(string $groupKey,string $opUserId='')
    {

        $res = DingTalk::requestPost('/topapi/attendance/groups/keytoid', [
            'group_key' => $groupKey,
            'op_user_id' => $opUserId,
        ]);
        return $res['result'];
    }

    /**
     * 批量新增地点
     * @param string $groupKey 考勤组ID
     * @param array $positionList postion列表，每次新增最多支持新增100个地点信息。
     * @param string $opUserid 操作人userId。
     * @return array
     * $positionList 格式如下：
     * [
     *  [
     *      'address':'',//地址描述。
     *      'foreign_id':'', //业务方positionId。
     *      'longitude':'', // 经度(支持6位小数)。
     *      'latitude':'', // 纬度(支持6位小数)。
     *      'offset':100, // offset
     *  ]
     * ]
     * @throws DingTalkException
     * @throws GuzzleException
     */
    public function groupPositionsAdd(string $groupKey, array $positionList, string $opUserid='')
    {

        $res = DingTalk::requestPost('/topapi/attendance/group/positions/add', [
            'group_key' => $groupKey,
            'op_userid' => $opUserid,
            'position_list' => $positionList,
        ]);
        return $res['result'];
    }
}