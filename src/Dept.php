<?php


namespace Shenhou\Dingtalk;


class Dept
{
    /**
     * 获取部门列表
     * @param string $dept_id 父部门ID。如果不传，默认部门为根部门，根部门ID为1。只支持查询下一级子部门，不支持查询多级子部门。
     * @param string $language 通讯录语言：zh_CN（默认）：中文；en_US：英文
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listsub($dept_id, $language = 'zh_CN')
    {
        return common::requestPost('/topapi/v2/department/listsub', [
            'dept_id' => $dept_id,
            'language' => $language
        ]);
    }

    /**
     * 创建部门
     * @param string $name 部门名称。长度限制为1~64个字符，不允许包含字符"-"","以及","。
     * @param int $parent_id 父部门ID，根部门ID为1。
     * @param false $hide_dept 是否隐藏本部门：true：隐藏部门，隐藏后本部门将不会显示在公司通讯录中false（默认值）：显示部门
     * @param array $dept_permits 指定可以查看本部门的其他部门列表，总数不能超过200。当hide_dept为true时，则此值生效。
     * @param array $user_permits 指定可以查看本部门的人员userid列表，总数不能超过200。当hide_dept为true时，则此值生效。
     * @param bool $outer_dept 是否限制本部门成员查看通讯录：true：开启限制。开启后本部门成员只能看到限定范围内的通讯录false（默认值）：不限制
     * @param bool $outer_dept_only_self 本部门成员是否只能看到所在部门及下级部门通讯录：true：只能看到所在部门及下级部门通讯录false：不能查看所有通讯录，在通讯录中仅能看到自己当outer_dept为true时，此参数生效。
     * @param array $outer_permit_users 指定本部门成员可查看的通讯录用户userid列表，总数不能超过200。当outer_dept为true时，此参数生效。
     * @param array $outer_permit_depts 指定本部门成员可查看的通讯录部门ID列表，总数不能超过200。当outer_dept为true时，此参数生效。
     * @param false $create_dept_group 是否创建一个关联此部门的企业群，默认为false即不创建。
     * @param false $auto_approve_apply 是否默认同意加入该部门的申请：true：表示加入该部门的申请将默认同意false：表示加入该部门的申请需要有权限的管理员同意
     * @param int $order 在父部门中的排序值，order值小的排序靠前。
     * @param string $source_identifier 部门标识字段，开发者可用该字段来唯一标识一个部门，并与钉钉外部通讯录里的部门做映射。
     */
    public function create($name, $parent_id = 1, $hide_dept = false, $dept_permits = [],
                           $user_permits = [], $outer_dept = true, $outer_dept_only_self = true,
                           $outer_permit_users = [], $outer_permit_depts = [], $create_dept_group = false,
                           $auto_approve_apply = false, $order = 999, $source_identifier = '')
    {
        $data = [
            'name' => $name,
            'parent_id' => $parent_id,
            'hide_dept' => $hide_dept,
            'dept_permits' => $dept_permits,
            'user_permits' => $user_permits,
            'outer_dept' => $outer_dept,
            'outer_dept_only_self' => $outer_dept_only_self,
            'outer_permit_users' => $outer_permit_users,
            'outer_permit_depts' => $outer_permit_depts,
            'create_dept_group' => $create_dept_group,
            'auto_approve_apply' => $auto_approve_apply,
            'order' => $order,
            'source_identifier' => $source_identifier,
        ];
        $res = common::requestPost('/topapi/v2/department/create', array_filter($data));
        return $res['dept_id'];
    }
}