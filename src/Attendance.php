<?php


namespace Shenhou\Dingtalk;


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
        $res = common::requestPost('/attendance/list', [
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
}