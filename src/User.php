<?php


namespace Shenhou\Dingtalk;


class User
{

    /**
     * 用免登code换取用户信息
     * @param string $code 钉钉免登code
     * @return array 用户信息
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $userid, string $language = 'zh_CN'): array
    {
        return common::requestPost('/topapi/v2/user/get', [
            'userid' => $userid,
            'language' => $language
        ]);
    }

    /**
     * 创建用户
     * @param string $name 员工名称，长度最大80个字符。
     * @param string $mobile 手机号码，企业内必须唯一，不可重复。如果是国际号码，请使用+xx-xxxxxx的格式。
     * @param array $dept_id_list 所属部门id列表，可通过获取部门列表接口获取。
     * @param string|null $userid 员工唯一标识ID（不可修改），企业内必须唯一。长度为1~64个字符，如果不传，将自动生成一个userid。
     * @param bool $hide_mobile 是否号码隐藏：true：隐藏 隐藏手机号后，手机号在个人资料页隐藏，但仍可对其发DING、发起钉钉免费商务电话。false：不隐藏
     * @param string|null $telephone 分机号，长度最大50个字符。企业内必须唯一，不可重复。
     * @param string|null $job_number 员工工号，长度最大为50个字符。
     * @param string|null $title 职位，长度最大为200个字符。
     * @param string|null $email 员工邮箱，长度最大50个字符。企业内必须唯一，不可重复
     * @param string|null $org_email 员工的企业邮箱，长度最大100个字符。员工的企业邮箱已开通，才能增加此字段。
     * @param string|null $work_place 办公地点，长度最大100个字符
     * @param string|null $remark 备注，长度最大2000个字符
     * @param array|null $dept_order_list 员工在对应的部门中的排序
     * @param array|null $dept_title_list 员工在对应的部门中的职位
     * @param array|null $extension 扩展属性，可以设置多种属性，最大长度2000个字符。
     * @param bool $senior_mode 是否开启高管模式：true：开启。开启后，手机号码对所有员工隐藏。普通员工无法对其发DING、发起钉钉免费商务电话。高管之间不受影响。false：不开启。
     * @param int|null $hired_date 入职时间，Unix时间戳，单位毫秒。
     * @param string|null $login_email 登录邮箱。
     * @param bool $exclusive_account 是否专属帐号。为true时，不能指定loginEmail或mobile）
     * @param string $exclusive_account_type 专属帐号类型：sso：企业自建专属帐号dingtalk：钉钉自建专属帐号
     * @param string|null $login_id 钉钉专属帐号登录名
     * @param string|null $init_password 钉钉专属帐号初始密码
     * @return string 用户userid
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(string $name, string $mobile, array $dept_id_list,
                           string $userid = null, bool $hide_mobile = false,
                           string $telephone = null, string $job_number = null,
                           string $title = null, string $email = null,
                           string $org_email = null, string $work_place = null, string $remark = null, array $dept_order_list = null,
                           array $dept_title_list = null, array $extension = null, bool $senior_mode = false,
                           int $hired_date = null, string $login_email = null, bool $exclusive_account = false, string $exclusive_account_type = 'dingtalk',
                           string $login_id = null, string $init_password = null
    )
    {
        $data = [
            'name' => $name,
            'mobile' => $mobile,
            'dept_id_list' => $dept_id_list,
            'userid' => $userid,
            'hide_mobile' => $hide_mobile,
            'telephone' => $telephone,
            'job_number' => $job_number,
            'title' => $title,
            'email' => $email,
            'org_email' => $org_email,
            'work_place' => $work_place,
            'remark' => $remark,
            'dept_order_list' => $dept_order_list,
            'dept_title_list' => $dept_title_list,
            'extension' => $extension,
            'senior_mode' => $senior_mode,
            'hired_date' => $hired_date,
            'login_email' => $login_email,
            'exclusive_account' => $exclusive_account,
            'exclusive_account_type' => $exclusive_account_type,
            'login_id' => $login_id,
            'init_password' => $init_password,
        ];
        $data = array_filter($data);
        $res = common::requestPost('/topapi/v2/user/create', $data);
        return $res['userid'];
    }

    /**
     * 更新用户
     * @param string $userid 员工的userid。
     * @param string|null $name 员工名称，长度最大80个字符。
     * @param string|null $mobile 手机号码，企业内必须唯一，不可重复。如果是国际号码，请使用+xx-xxxxxx的格式。
     * @param array|null $dept_id_list 所属部门id列表，可通过获取部门列表接口获取。
     * @param bool $hide_mobile 是否号码隐藏：true：隐藏 隐藏手机号后，手机号在个人资料页隐藏，但仍可对其发DING、发起钉钉免费商务电话。false：不隐藏
     * @param string|null $telephone 分机号，长度最大50个字符。企业内必须唯一，不可重复。
     * @param string|null $job_number 员工工号，长度最大为50个字符。
     * @param string|null $title 职位，长度最大为200个字符。
     * @param string|null $email 员工邮箱，长度最大50个字符。企业内必须唯一，不可重复
     * @param string|null $org_email 员工的企业邮箱，长度最大100个字符。员工的企业邮箱已开通，才能增加此字段。
     * @param string|null $work_place 办公地点，长度最大100个字符
     * @param string|null $remark 备注，长度最大2000个字符
     * @param array|null $dept_order_list 员工在对应的部门中的排序
     * @param array|null $dept_title_list 员工在对应的部门中的职位
     * @param array|null $extension 扩展属性，可以设置多种属性，最大长度2000个字符。
     * @param bool $senior_mode 是否开启高管模式：true：开启。开启后，手机号码对所有员工隐藏。普通员工无法对其发DING、发起钉钉免费商务电话。高管之间不受影响。false：不开启。
     * @param int|null $hired_date 入职时间，Unix时间戳，单位毫秒。
     * @param string|null $login_email 登录邮箱。
     * @param bool $exclusive_account 是否专属帐号。为true时，不能指定loginEmail或mobile）
     * @param string $exclusive_account_type 专属帐号类型：sso：企业自建专属帐号dingtalk：钉钉自建专属帐号
     * @param string|null $login_id 钉钉专属帐号登录名
     * @param string|null $init_password 钉钉专属帐号初始密码
     * @return string 用户userid
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(string $userid, string $name = null, string $mobile = null, array $dept_id_list = null,
                           bool $hide_mobile = false,
                           string $telephone = null, string $job_number = null,
                           string $title = null, string $email = null,
                           string $org_email = null, string $work_place = null, string $remark = null, array $dept_order_list = null,
                           array $dept_title_list = null, array $extension = null, bool $senior_mode = false,
                           int $hired_date = null, string $login_email = null, bool $exclusive_account = false, string $exclusive_account_type = 'dingtalk',
                           string $login_id = null, string $init_password = null
    )
    {
        $data = [
            'name' => $name,
            'mobile' => $mobile,
            'dept_id_list' => $dept_id_list,
            'userid' => $userid,
            'hide_mobile' => $hide_mobile,
            'telephone' => $telephone,
            'job_number' => $job_number,
            'title' => $title,
            'email' => $email,
            'org_email' => $org_email,
            'work_place' => $work_place,
            'remark' => $remark,
            'dept_order_list' => $dept_order_list,
            'dept_title_list' => $dept_title_list,
            'extension' => $extension,
            'senior_mode' => $senior_mode,
            'hired_date' => $hired_date,
            'login_email' => $login_email,
            'exclusive_account' => $exclusive_account,
            'exclusive_account_type' => $exclusive_account_type,
            'login_id' => $login_id,
            'init_password' => $init_password,
        ];
        $data = array_filter($data);
        common::requestPost('/topapi/v2/user/update', $data);
        return true;
    }


}