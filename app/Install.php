<?php

namespace app;

use app\model\SystemAuthRule;
use app\model\SystemConfig;
use Exception;
use think\facade\Db;

/**
 * @title SAAS框架
 * @desc 类描述
 * @author 楚羽幽 <cy958416459@qq.com>
 */
class Install
{
    /**
     * 安装前置操作
     * @param mixed $version
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function beforeUpdate($version)
    {
    }

    /**
     * 安装后置操作
     * @param mixed $version
     * @param mixed $context
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function update($version, $context)
    {
        # 更新composer
        self::updateComposer();
        # 修复配置项
        self::fixConfig();
        # 更新配置项
        self::insertConfig();
        # 更新附件库
        self::saveMenus();
        # 升级附件库独立数据
        self::uploadifyData();
    }

    /**
     * 升级附件库为独立数据
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function uploadifyData()
    {
        # 获取前缀
        $default = config('thinkorm.default');
        $connections = config('thinkorm.connections');
        isset($connections[$default]['prefix']) && $prefix = $connections[$default]['prefix'];
        # 租户新增菜单
        $sql = "INSERT INTO `{$prefix}store_menus` VALUES (32, '2023-06-16 11:44:08', '2023-06-16 11:44:08', 'store', 'Platform/del', '\\app\\store\\controller\\', 21, '删除平台', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0')";
        Db::execute($sql);
        # 平台配置增加删除时间
        $sql = "ALTER TABLE `{$prefix}store_platform_config` ADD COLUMN `delete_time` datetime NULL AFTER `update_at`;";
        Db::execute($sql);
        # 租户平台增加删除时间
        $sql = "ALTER TABLE `{$prefix}store_platform` ADD COLUMN `delete_time` datetime NULL AFTER `update_at`;";
        Db::execute($sql);
        # 附件库分类增加字段
        $sql = "ALTER TABLE `{$prefix}system_upload_cate` ADD COLUMN `delete_time` datetime NULL AFTER `update_at`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload_cate` ADD COLUMN `store_id` datetime NULL AFTER `delete_time`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload_cate` ADD COLUMN `platform_id` datetime NULL AFTER `store_id`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload_cate` ADD COLUMN `appid` datetime NULL AFTER `platform_id`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload_cate` ADD COLUMN `uid` datetime NULL AFTER `appid`;";
        Db::execute($sql);
        # 附件库增加字段
        $sql = "ALTER TABLE `{$prefix}system_upload` ADD COLUMN `delete_time` datetime NULL AFTER `update_at`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload` ADD COLUMN `store_id` datetime NULL AFTER `delete_time`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload` ADD COLUMN `platform_id` datetime NULL AFTER `store_id`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload` ADD COLUMN `appid` datetime NULL AFTER `platform_id`;";
        $sql .= "ALTER TABLE `{$prefix}system_upload` ADD COLUMN `uid` datetime NULL AFTER `appid`;";
        Db::execute($sql);
    }

    /**
     * 修改配置项
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function fixConfig()
    {
        # 版权名称
        $where = ['name' => 'store_copyright_name'];
        $count  = SystemConfig::where($where)->count();
        if ($count >= 2) {
            SystemConfig::where($where)->delete();
        }
        # 系统教程
        $where = ['name' => 'store_copyright_tutorial'];
        $count  = SystemConfig::where($where)->count();
        if ($count >= 2) {
            SystemConfig::where($where)->delete();
        }
        # 专属客服
        $where = ['name' => 'store_copyright_service'];
        $count  = SystemConfig::where($where)->count();
        if ($count >= 2) {
            SystemConfig::where($where)->delete();
        }
    }

    /**
     * 更新菜单项
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function saveMenus()
    {
        $where = [
            ['path', '=', 'SystemUpload/config']
        ];
        $menu  = SystemAuthRule::where($where)->find();
        if (!$menu) {
            $model            = new SystemAuthRule;
            $model->module    = 'admin';
            $model->path      = 'SystemUpload/config';
            $model->namespace = '\\app\\admin\\controller\\';
            $model->pid       = 41;
            $model->title     = '附件库设置';
            $model->method    = ['GET', 'PUT'];
            $model->is_api    = '1';
            $model->component = 'form/index';
            $model->show      = '1';
            if (!$model->save()) {
                throw new Exception('更新菜单失败');
            }
            # 设置是否显示
            SystemAuthRule::where(['path' => 'Uploadify/tabs'])->save(['show' => '1', 'pid' => 7]);
            SystemAuthRule::where(['path' => 'SystemUpload/index'])->save(['show' => '0']);
            SystemAuthRule::where(['path' => 'SystemUploadCate/index'])->save(['show' => '0']);
        }
        # 更新菜单位置
        $where = [
            ['path', '=', 'Uploadify/tabs']
        ];
        $menuModel  = SystemAuthRule::where($where)->find();
        if ($menuModel) {
            if ($menuModel->pid !== 2) {
                SystemAuthRule::where(['path' => 'Uploadify/tabs'])->save(['show' => '1', 'pid' => 7]);
                SystemAuthRule::where(['path' => 'SystemUpload/index'])->save(['show' => '0']);
                SystemAuthRule::where(['path' => 'SystemUploadCate/index'])->save(['show' => '0']);
            }
        }
    }

    /**
     * 写入配置项
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function insertConfig()
    {
        $model          = SystemConfig::where(['name'=> 'store_copyright_name'])->find();
        if (!$model) {
            $data = [
                'cid' => 1,
                'title' => '租户版权',
                'name' => 'store_copyright_name',
                'value' => '贵州猿创科技有限公司',
                'component' => 'input',
                'placeholder' => '展示在租户统计页面的版权名称',
            ];
            (new SystemConfig)->save($data);
        }
        $model          = SystemConfig::where(['name'=> 'store_copyright_tutorial'])->find();
        if (!$model) {
            $data = [
                'cid' => 1,
                'title' => '系统教程',
                'name' => 'store_copyright_tutorial',
                'value' => '使用文档|http://www.kfadmin.net/#/document
                在线社区|http://www.kfadmin.net/#/document
                微信群|http://www.kfadmin.net/#/document',
                'component' => 'textarea',
                'placeholder' => '一行一个信息，示例：名称|网址',
            ];
            (new SystemConfig)->save($data);
        }
        $model          = SystemConfig::where(['name'=> 'store_copyright_service'])->find();
        if (!$model) {
            $data = [
                'cid' => 1,
                'title' => '专属客服',
                'name' => 'store_copyright_service',
                'value' => '18786709420（微信同号）',
                'component' => 'input',
                'placeholder' => '客服展示信息',
            ];
            (new SystemConfig)->save($data);
        }
    }

    /**
     * 更新composer数据
     *
     * @return void
     */
    private static function updateComposer()
    {
        $newComposerPath = app_path('/composer.txt');
        $newComposer = self::composerJSON($newComposerPath);
        if (!$newComposer) {
            console_log('无需更新composer');
            return;
        }
        $oldComposerPath = base_path('/composer.json');
        $oldComposer     = self::composerJSON($oldComposerPath);
        if ($oldComposer) {
            $newComposer['require'] = $oldComposer['require'];
        }
        if (!isset($oldComposer['prefer-stable'])) {
            unset($oldComposer['prefer-stable']);
        }
        if (!isset($oldComposer['minimum-stability'])) {
            unset($oldComposer['minimum-stability']);
        }
        if (!isset($oldComposer['repositories'])) {
            unset($oldComposer['repositories']);
        }
        $data = json_encode($newComposer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $data = str_replace("\\/", '/', $data);
        file_put_contents($oldComposerPath, $data);
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        # 执行更新命令
        $composerCommand = 'export COMPOSER_HOME=/www/server/php/80/bin;COMPOSER_ALLOW_SUPERUSER=1;';
        $command         = "{$composerCommand}composer update --no-interaction 2>&1";
        $output          = shell_exec($command);
        # 删除新的composer模板文件
        if (file_exists($newComposerPath)) {
            unlink($newComposerPath);
        }
        p($output, '框架更新结果');
        var_dump($output);
        if ($output === null) {
            console_log("composer安装失败");
        } else {
            // 停止进程
            posix_kill(posix_getppid(), SIGINT);
        }
    }

    /**
     * 获取composer
     *
     * @param  string     $path
     * @return array|bool
     */
    private static function composerJSON(string $path): array|bool
    {
        if (!file_exists($path)) {
            return false;
        }
        $data = file_get_contents($path);
        $data = json_decode($data, true);
        if (!$data) {
            return false;
        }
        return $data;
    }
}