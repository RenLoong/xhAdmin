<?php

namespace app\admin\utils;

use app\admin\model\SystemAdmin;
use Exception;
use app\admin\model\SystemAdminRole;
use app\admin\model\SystemAuthRule;

/**
 * @title 前端Vue路由数据管理器
 * @desc 仅用于获取管理员权限规则返回给前端
 * @author 楚羽幽 <admin@hangpu.net>
 */
class VueRoutesMgr
{
    // 设置允许输出字段
    private static $visible = [
        'id',
        'module',
        'pid',
        'path',
        'title',
        'method',
        'component',
        'auth_params',
        'icon',
        'show',
    ];

    /**
     * 获取管理员菜单与Vue路由
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  SystemAdmin $adminModel
     * @return array
     */
    public static function run(SystemAdmin $adminModel): array
    {
        // 默认选择菜单
        $active = 'Index/index';
        // 获取管理员权限
        $adminRoleRule = self::getAdminRoleRule($adminModel);
        return ['active' => $active, 'list' => $adminRoleRule];
    }

    /**
     * 获取部门权限
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  SystemAdmin $adminModel
     * @return array
     */
    public static function getAdminRoleRule(SystemAdmin $adminModel): array
    {
        $where     = [
            'id' => $adminModel->role_id
        ];
        $roleModel = SystemAdminRole::where($where)
            ->field(['rule', 'is_system'])
            ->find();
        if (!$roleModel) {
            throw new Exception('该部门不存在');
        }
        // 已授权规则
        $rule = $roleModel->rule;
        // 默认查询全部权限
        $model = SystemAuthRule::order(['sort' => 'asc', 'id' => 'asc']);
        if ($roleModel->is_system != '1') {
            // 普通级部门（查询已授权规则）
            $data = $model->whereIn('path', $rule)->field(self::$visible)
                ->select()
                ->each(function ($e) {
                    $e->method = current($e->method);
                    return $e;
                })->toArray();
            // 递归查询父级权限
            foreach ($data as $value) {
                if (!in_array($value['pid'], $rule) && $value['pid']) {
                    self::getParentRule($data, $value['pid']);
                }
            }
            // 两次排序
            $data = list_sort_by($data, 'id', 'asc');
            $data = list_sort_by($data, 'sort', 'asc');
        }
        else {
            // 系统级部门（查询全部规则返回）
            $data = $model->field(self::$visible)
                ->select()
                ->each(function ($e) {
                    $e->method = current($e->method);
                    return $e;
                })->toArray();
        }

        // 返回数据
        return $data;
    }

    /**
     * 获取部门权限规则列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  SystemAdmin $adminModel
     * @return array
     */
    public static function getAdminRoleColumn(SystemAdmin $adminModel): array
    {
        $rule = self::getAdminRoleRule($adminModel);
        $data = [];
        foreach ($rule as $key => $value) {
            $data[$key] = $value['path'];
        }
        return $data;
    }

    /**
     * 递归查询父级规则
     *
     * @param string $rule
     * @return array
     */
    private static function getParentRule(array &$list, string $rule): array
    {
        $where = [
            ['path', '=', $rule],
        ];
        $model = SystemAuthRule::where($where)
            ->field(self::$visible)
            ->find();
        if (!$model) {
            throw new Exception('父级规则不存在');
        }
        $data = $model->toArray();
        array_push($list, $data);
        if ($data['pid']) {
            self::getParentRule($list, $data['pid']);
        }
        return $list;
    }
}