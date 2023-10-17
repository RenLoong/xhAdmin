<?php

namespace plugin\{TEAM_PLUGIN_NAME}\app\model;

use app\common\utils\Password;
use app\common\service\UploadService;

/**
 * 管理员模型
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-11
 */
class PluginAdmin extends AppidModel
{
    # 隐藏字段
    protected $hidden = [
        'password'
    ];

    /**
     * 关联等级
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-02
     * @return void
     */
    public function role()
    {
        return $this->hasOne(PluginRoles::class, 'id', 'role_id');
    }
    
    /**
     * 获取头像URL
     * @param mixed $value
     * @return string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function getHeadimgAttr($value)
    {
        return $value ? UploadService::url((string)$value) : '';
    }

    /**
     * 密码加密写入
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-08
     * @param  type $value
     * @return void
     */
    protected function setPasswordAttr($value)
    {
        if (!$value) {
            return false;
        }
        return Password::passwordHash((string)$value);;
    }

    /**
    * 获取管理员角色规则
    * @author 贵州猿创科技有限公司
    * @Email 416716328@qq.com
    * @DateTime 2023-03-11
    */
    public static function getAdminRule(int $adminId)
    {
        return [];
    }
    
    /**
     * 获取管理员组件选项
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  integer $admin_id
     * @return array
     */
    public function selectOptions(int $admin_id): array
    {
        $where = [
            ['pid', '=', $admin_id],
        ];
        $field = [
            'id as value',
            'username as label'
        ];
        $list = self::where($where)
            ->field($field)
            ->select()
            ->toArray();
        return $list;
    }

    /**
     * 获取角色组件选项
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  integer $admin_id
     * @return array
     */
    public function selectRolesOptions(int $admin_id): array
    {
        return (new PluginRoles)->selectOptions($admin_id);
    }
}
