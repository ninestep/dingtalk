<?php


namespace Shenhou\Dingtalk;


class Dept
{
    /**
     * 获取部门列表
     * @param string $dept_id 父部门ID。如果不传，默认部门为根部门，根部门ID为1。只支持查询下一级子部门，不支持查询多级子部门。
     * @param string $language 通讯录语言：zh_CN（默认）：中文；en_US：英文
     * @return array
     */
    public function listsub($dept_id, $language = 'zh_CN')
    {
        return common::requestPost('/topapi/v2/department/listsub',[
            'dept_id'=>$dept_id,
            'language'=>$language
        ]);
    }
}