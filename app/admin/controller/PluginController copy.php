<?php

namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\admin\logic\PluginLogic;
use app\admin\service\kfcloud\SystemInfo;
use app\admin\utils\ComposerMgr;
use app\BaseController;
use app\enum\PluginType;
use app\enum\PluginTypeStyle;
use app\utils\Utils;
use process\Monitor;
use support\Request;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\PluginRequest;
use ZipArchive;

/**
 * 插件管理
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-05
 */
class PluginController extends BaseController
{
    /**
     * 表格
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data = $builder
            ->addActionOptions('操作', [
                'width' => 230
            ])
            ->pageConfig()
            ->addTopButton(
                'cloud',
                '云服务',
                [
                    'type' => 'remote',
                    'modal' => true,
                    'api' => 'admin/PluginCloud/index',
                    'path' => 'remote/cloud/index'
                ],
                [
                    'title' => '云服务中心',
                    'width' => '350px'
                ],
                [
                    'type' => 'success'
                ]
            )
            ->addRightButton(
                'doc',
                '文档',
                [
                    'type' => 'link',
                    'api' => 'admin/Plugin/getDoc',
                    'aliasParams' => [
                        'name',
                        'version'
                    ],
                ],
                [],
                [
                    'type' => 'warning'
                ]
            )
            ->addRightButton(
                'update',
                '更新',
                [
                    'type' => 'remote',
                    'modal' => true,
                    'api' => 'admin/Plugin/update',
                    'path' => 'remote/cloud/update',
                    'params' => [
                        'field' => 'is_update',
                        'value' => 'update',
                    ],
                    'aliasParams' => [
                        'name',
                        'version'
                    ],
                ],
                [
                    'title' => '更新应用',
                    'width' => '45%',
                ],
                [
                    'type' => 'success'
                ]
            )
            ->addRightButton(
                'install',
                '安装',
                [
                    'type' => 'remote',
                    'modal' => true,
                    'api' => 'admin/Plugin/install',
                    'path' => 'remote/cloud/install',
                    'params' => [
                        'field' => 'installed',
                        'value' => 'install',
                    ],
                    'aliasParams' => [
                        'name',
                        'version'
                    ],
                ],
                [
                    'title' => '应用安装',
                    'width' => '45%',
                ],
                [
                    'type' => 'success'
                ]
            )
            ->addRightButton(
                'uninstall',
                '卸载',
                [
                    'type' => 'confirm',
                    'api' => 'admin/Plugin/uninstall',
                    'path' => 'remote/cloud/uninstall',
                    'method' => 'delete',
                    'params' => [
                        'field' => 'installed',
                        'value' => 'uninstall',
                    ],
                    'aliasParams' => [
                        'name',
                        'version'
                    ],
                ],
                [
                    'title' => '温馨提示',
                    'content' => "是否确认卸载该应用插件？\n该操作者将不可恢复数据，请自行备份应用数据",
                ],
                [
                    'type' => 'error'
                ]
            )
            ->tabsConfig([
                'active' => '0',
                'field' => 'active',
                'list' => [
                    [
                        'label' => '全部',
                        'value' => '0',
                    ],
                    [
                        'label' => '未安装',
                        'value' => '1',
                    ],
                    [
                        'label' => '已安装',
                        'value' => '2',
                    ],
                ]
            ])
            ->addColumn('title', '名称')
            ->addColumn('version_name', '版本', [
                'width' => 100
            ])
            ->addColumn('dev.title', '开发者')
            ->addColumnEle('money', '价格', [
                'width' => 150,
                'params' => [
                    'type' => 'money'
                ]
            ])
            ->addColumnEle('logo', '图标', [
                'width' => 80,
                'params' => [
                    'type' => 'image',
                ],
            ])
            ->addColumnEle('platform_icon', '支持平台', [
                'params' => [
                    'type' => 'images',
                    'previewDisabled' => true
                ],
            ])
            ->addColumnEle('plugin_type', '应用类型', [
                'width' => 100,
                'params' => [
                    'type' => 'tags',
                    'options' => PluginType::dictOptions(),
                    'style' => PluginTypeStyle::parseAlias('type'),
                ],
            ])
            ->addColumn('min_version', '版本要求', [
                'width' => 100,
                'titlePrefix' => [
                    'content' => '该应用对于主框架的版本最低要求',
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public function index(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $active = $request->get('active', '0');

        $installed = PluginLogic::getLocalPlugins();
        $systemInfo = SystemInfo::info();
        $query = [
            'active' => $active,
            'page' => $page,
            'plugins' => $installed,
            'saas_version' => $systemInfo['system_version']
        ];
        $req = new PluginRequest;
        $req->list();
        $req->setQuery($query, null);
        $cloud = new Cloud($req);
        $data = $cloud->send()->toArray();
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]['min_version'] = "无";
            if ($value['saas_version']) {
                $data['data'][$key]['min_version'] = $value['saas_version'];
            }
        }
        return $this->successRes($data);
    }

    /**
     * 获取文档地址
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public function getDoc(Request $request)
    {
        $name = $request->get('name');
        $version = $request->get('version');
        $systemInfo = SystemInfo::info();
        $installed_version = PluginLogic::getPluginVersion($name);
        $req = new PluginRequest;
        $req->detail();
        $req->name = $name;
        $req->version = $version;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installed_version;
        $cloud = new Cloud($req);
        $data = $cloud->send()->toArray();
        return $this->successRes(['url' => $data['doc_url']]);
    }

    /**
     * 购买应用
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public function buy(Request $request)
    {
        $name = $request->post('name');
        $version = $request->post('version');
        $coupon_code = $request->post('coupon_code');

        $systemInfo = SystemInfo::info();
        $installed_version = PluginLogic::getPluginVersion($name);
        $req = new PluginRequest;
        $req->buy();
        $req->name = $name;
        $req->version = $version;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installed_version;
        $req->coupon_code = $coupon_code;
        $cloud = new Cloud($req);
        $data = $cloud->send()->toArray();
        return $this->successRes($data);
    }

    /**
     * 应用插件详情
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public function detail(Request $request)
    {
        $name = $request->get('name');
        $version = $request->get('version');

        $systemInfo = SystemInfo::info();
        $installed_version = PluginLogic::getPluginVersion($name);
        $req = new PluginRequest;
        $req->detail();
        $req->name = $name;
        $req->version = $version;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installed_version;
        $cloud = new Cloud($req);
        $data = $cloud->send()->toArray();

        $localVersion = PluginLogic::getPluginVersion($name);
        $data['localVersion'] = $localVersion;
        return $this->successRes($data);
    }

    /**
     * 安装应用
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public function install(Request $request)
    {
        $name = $request->post('name');
        $version = $request->post('version');

        # 检测应用是否已安装
        $installed_version = PluginLogic::getPluginVersion($name);
        if ($installed_version > 1) {
            return $this->fail('该应用已安装');
        }

        // 获取插件信息

        $systemInfo = SystemInfo::info();
        $req = new PluginRequest;
        $req->detail();
        $req->name = $name;
        $req->version = $version;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installed_version;
        $cloud = new Cloud($req);
        $data = $cloud->send();
        // 获取下载文件url
        $req = new PluginRequest;
        $req->getKey();
        $req->name = $name;
        $req->version = $version;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installed_version;
        $cloud = new Cloud($req);
        $data = $cloud->send();
        // 下载zip文件
        $request = new \YcOpen\CloudService\Request();
        # 通过获取下载密钥接口获得
        $request->setUrl($data->url);
        # 保存文件到指定路径

        $base_path = base_path("/plugin/{$name}");
        $zip_file = "{$base_path}.zip";
        $extract_to = base_path('/plugin/');
        $request->setSaveFile($zip_file);
        $cloud = new Cloud($request);
        $status = $cloud->send();
        if (!$status) {
            return $this->fail('安装包下载失败');
        }
        console_log("{$name}应用插件文件下载完成");
        // 效验系统函数
        $has_zip_archive = class_exists(ZipArchive::class, false);
        if (!$has_zip_archive) {
            $cmd = PluginLogic::getUnzipCmd($zip_file, $extract_to);
            if (!$cmd) {
                return $this->fail('请给php安装zip模块或者给系统安装unzip命令');
            }
            if (!function_exists('proc_open')) {
                return $this->fail('请解除proc_open函数的禁用或者给php安装zip模块');
            }
        }
        if (!function_exists('shell_exec')) {
            return $this->fail('请开启shell_exec函数');
        }

        $monitor_support_pause = method_exists(Monitor::class, 'pause');
        if ($monitor_support_pause) {
            Monitor::pause();
        }
        try {
            # 解压zip到plugin目录
            if ($has_zip_archive) {
                $zip = new ZipArchive;
                $zip->open($zip_file, ZIPARCHIVE::CHECKCONS);
            }
            $install_class = "\\plugin\\{$name}\\api\\Install";
            if (!empty($zip)) {
                $zip->extractTo(base_path('/plugin/'));
                unset($zip);
            } else {
                PluginLogic::unzipWithCmd($cmd);
            }
            # 检测composer
            ComposerMgr::composerMergePlugin($name);
            # 删除压缩包
            unlink($zip_file);
            # 执行install安装
            if (class_exists($install_class) && method_exists($install_class, 'install')) {
                call_user_func([$install_class, 'install'], $version);
            }
            # 输出安装完成
            console_log("{$name} --- 安装完成");
        } catch (\Throwable $e) {
            # 安装失败，删除安装目录
            $plugin_dir = base_path("/plugin/{$name}");
            if (is_dir($plugin_dir)) {
                chdir($plugin_dir);
                shell_exec("rm -rf {$plugin_dir}");
            }
            return $this->failFul($e->getMessage(), $e->getCode());
        } finally {
            if ($monitor_support_pause) {
                Monitor::resume();
            }
        }
        // 停止框架
        Utils::reloadWebman();

        // 执行返回
        return $this->success('安装成功');
    }

    /**
     * 更新
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-09
     */
    public function update(Request $request)
    {
        $name = $request->post('name');
        $version = $request->post('version');
        $installed_version = PluginLogic::getPluginVersion($name);
        if (!$installed_version) {
            return $this->fail('该应用未安装');
        }
        try {
            # 执行备份应用
            PluginLogic::backup($name);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
        # 执行删除文件
        chdir(base_path("/plugin/{$name}"));
        shell_exec("rm -rf ./*");
        # 获取插件信息
        $systemInfo = SystemInfo::info();
        $req = new PluginRequest;
        $req->detail();
        $req->name = $name;
        $req->version = $version;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installed_version;
        $cloud = new Cloud($req);
        $cloud->send()->toArray();
        // 获取下载文件url
        // 下载zip文件
        $req = new PluginRequest;
        $req->getKey();
        $req->name = $name;
        $req->version = $version;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installed_version;
        $cloud = new Cloud($req);
        $data = $cloud->send();

        $request = new \YcOpen\CloudService\Request();
        # 通过获取下载密钥接口获得
        $request->setUrl($data->url);
        # 保存文件到指定路径
        $base_path = base_path("/plugin/{$name}");
        $zip_file = "{$base_path}.zip";
        $extract_to = base_path('/plugin/');
        $request->setSaveFile($zip_file);
        $cloud = new Cloud($request);
        $status = $cloud->send();
        if (!$status) {
            return $this->fail('安装包下载失败');
        }
        console_log("{$name}应用插件文件下载完成");
        // 效验系统函数
        $has_zip_archive = class_exists(ZipArchive::class, false);
        if (!$has_zip_archive) {
            $cmd = PluginLogic::getUnzipCmd($zip_file, $extract_to);
            if (!$cmd) {
                return $this->fail('请给php安装zip模块或者给系统安装unzip命令');
            }
            if (!function_exists('proc_open')) {
                return $this->fail('请解除proc_open函数的禁用或者给php安装zip模块');
            }
        }
        if (!function_exists('shell_exec')) {
            return $this->fail('请开启shell_exec函数');
        }

        $monitor_support_pause = method_exists(Monitor::class, 'pause');
        if ($monitor_support_pause) {
            Monitor::pause();
        }
        try {
            # 解压zip到plugin目录
            if ($has_zip_archive) {
                $zip = new ZipArchive;
                $zip->open($zip_file, ZIPARCHIVE::CHECKCONS);
            }
            # 解压目录
            if (!empty($zip)) {
                $zip->extractTo(base_path('/plugin/'));
                unset($zip);
            } else {
                PluginLogic::unzipWithCmd($cmd);
            }

            $context = null;
            $install_class = "\\plugin\\{$name}\\api\\Install";
            # 执行beforeUpdate
            if (class_exists($install_class) && method_exists($install_class, 'beforeUpdate')) {
                $context = call_user_func([$install_class, 'beforeUpdate'], $installed_version, $version);
            }
            # 检测composer
            ComposerMgr::composerMergePlugin($name);
            # 删除压缩包
            unlink($zip_file);
            # 执行update更新
            if (class_exists($install_class) && method_exists($install_class, 'update')) {
                call_user_func([$install_class, 'update'], $installed_version, $version, $context);
            }
            # 输出更新完成
            console_log("{$name} --- 更新完成");
        } catch (\Throwable $e) {
            try {
                # 更新应用失败，回滚代码
                PluginLogic::rollback($name);
            } catch (\Throwable $e) {
                return $this->fail($e->getMessage());
            }
            return $this->json($e->getMessage(), 404, [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ]);
        } finally {
            if ($monitor_support_pause) {
                Monitor::resume();
            }
        }
        // 停止框架
        Utils::reloadWebman();

        // 执行返回
        return $this->success('更新成功');
    }

    /**
     * 卸载应用
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public function uninstall(Request $request)
    {
        $name = $request->post('name');
        $version = $request->post('version');
        if (!$name || !preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            return $this->fail('参数错误');
        }

        # 获得插件路径
        clearstatcache();
        $path = get_realpath(base_path("/plugin/{$name}"));
        if (!$path || !is_dir($path)) {
            return $this->success('卸载成功');
        }
        # 执行uninstall卸载
        $install_class = "\\plugin\\{$name}\\api\\Install";
        if (class_exists($install_class) && method_exists($install_class, 'uninstall')) {
            call_user_func([$install_class, 'uninstall'], $version);
        }
        # 删除目录
        clearstatcache();
        if (is_dir($path)) {
            $monitor_support_pause = method_exists(Monitor::class, 'pause');
            if ($monitor_support_pause) {
                Monitor::pause();
            }
            try {
                PluginLogic::rmDir($path);
            } finally {
                if ($monitor_support_pause) {
                    Monitor::resume();
                }
            }
        }
        # 检测composer
        ComposerMgr::composerMergePlugin($name);
        clearstatcache();

        # 重启主进程
        Utils::reloadWebman();
        # 返回操作结果
        return $this->success('卸载成功');
    }
}