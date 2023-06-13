<?php

namespace app;
use app\model\SystemAuthRule;
use app\model\SystemConfig;
use Exception;

/**
 * @title SAAS框架
 * @desc 类描述
 * @author 楚羽幽 <cy958416459@qq.com>
 */
class Install
{
    /**
     * 安装前置操作
     *
     * @param  type $version
     * @return void
     */
    public static function beforeUpdate($version)
    {
    }

    /**
     * 安装后置操作
     *
     * @param  type $version
     * @param  type $context
     * @return void
     */
    public static function update($version, $context)
    {
        # 更新composer
        self::updateComposer();
        # 更新配置项
        self::insertConfig();
        # 更新附件库
        self::saveMenus();
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
            ['path','=','SystemUpload/config']
        ];
        $menu = SystemAuthRule::where($where)->find();
        if (!$menu) {
            $model = new SystemAuthRule;
            $model->module = 'admin';
            $model->path = 'SystemUpload/config';
            $model->namespace = '\\app\\admin\\controller\\';
            $model->pid = 41;
            $model->title = '附件库设置';
            $model->method = ['GET','PUT'];
            $model->is_api = '1';
            $model->component = 'form/index';
            $model->show = '1';
            if (!$model->save()) {
                throw new Exception('更新菜单失败');
            }
            # 设置是否显示
            SystemAuthRule::where(['path'=> 'Uploadify/tabs'])->save(['show'=> '1','pid'=> 7]);
            SystemAuthRule::where(['path'=> 'SystemUpload/index'])->save(['show'=> '0']);
            SystemAuthRule::where(['path'=> 'SystemUploadCate/index'])->save(['show'=> '0']);
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
        $copyright_name = getHpConfig('store_copyright_name');
        if (!$copyright_name) {
            $configs = [
                [
                    'cid'           => 1,
                    'title'         => '租户版权',
                    'name'          => 'store_copyright_name',
                    'value'         => '贵州猿创科技有限公司',
                    'component'     => 'input',
                    'placeholder'   => '展示在租户统计页面的版权名称',
                ],
                [
                    'cid'           => 1,
                    'title'         => '系统教程',
                    'name'          => 'store_copyright_tutorial',
                    'value'         => '使用文档|http://www.kfadmin.net/#/document
                    在线社区|http://www.kfadmin.net/#/document
                    微信群|http://www.kfadmin.net/#/document',
                    'component'     => 'textarea',
                    'placeholder'   => '一行一个信息，示例：名称|网址',
                ],
                [
                    'cid'           => 1,
                    'title'         => '专属客服',
                    'name'          => 'store_copyright_service',
                    'value'         => '18786709420（微信同号）',
                    'component'     => 'input',
                    'placeholder'   => '客服展示信息',
                ],
            ];
            (new SystemConfig)->saveAll($configs);
        }
    }

    /**
     * 更新composer数据
     *
     * @return void
     */
    private static function updateComposer()
    {
        $newComposer = self::composerJSON(app_path('/composer.txt'));
        if (!$newComposer) {
            console_log('无需更新composer');
            return;
        }
        $oldComposerPath = base_path('/composer.json');
        $oldComposer = self::composerJSON($oldComposerPath);
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
        $command = "{$composerCommand}composer update --no-interaction 2>&1";
        $output = shell_exec($command);
        p($output,'框架更新结果');
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
