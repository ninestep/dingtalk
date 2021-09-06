<?php


namespace Shenhou\Dingtalk;


class Role
{
    /**
     * 获取指定角色的员工列表
     * @param string $role_id 角色ID。
     * @param int $offset 支持分页查询，与offset参数同时设置时才生效，此参数代表分页大小，默认值20，最大100。
     * @param int $size 支持分页查询，与size参数同时设置时才生效，此参数代表偏移量，偏移量从0开始。
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function simplelist(string $role_id, int $offset=0, int $size=100): array
    {
        return DingTalk::requestPost('/topapi/role/simplelist',[
            'role_id'=>$role_id,
            'size'=>$size,
            'offset'=>$offset
        ]);
    }

    /**
     * 获取角色列表
     * @param int $offset 支持分页查询，与offset参数同时设置时才生效，此参数代表分页大小，默认值20，最大100。
     * @param int $size 支持分页查询，与size参数同时设置时才生效，此参数代表偏移量，偏移量从0开始。
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(int $offset=0, int $size=200): array
    {
        return DingTalk::requestPost('/topapi/role/list',[
            'size'=>$size,
            'offset'=>$offset
        ]);
    }
}