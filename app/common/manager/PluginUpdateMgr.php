<?php
namespace app\common\manager;

use app\common\service\SystemInfoService;
use app\utils\JsonMgr;
use Exception;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\PluginRequest;
use ZipArchive;
use Workerman\Timer;
use Workerman\Worker;

class PluginUpdateMgr
{
    /**
     * 应用名称
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $name;

    /**
     * 应用版本
     * @var int
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $version;

    /**
     * 已安装版本
     * @var int
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $installVersion;

    /**
     * 获取系统信息
     * @var SystemInfoService
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $systemInfo = null;

    /**
     * 解压路径根目录
     * @var 
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $extractTo = null;

    /**
     * 插件路径
     * @var 
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $pluginPath = null;

    /**
     * 插件临时储存路径
     * @var 
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $pluginTempPath = null;

    /**
     * 插件压缩包路径
     * @var 
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $zipFile = null;

    /**
     * CMD命令检测
     * @var 
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $cmd = null;

    /**
     * 构造方法
     * @param mixed $name
     * @param mixed $version
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct($name, $version)
    {
        $this->extractTo  = base_path('/plugin');
        $this->pluginPath = "{$this->extractTo}/{$name}";
        $this->pluginTempPath = runtime_path("/plugin/{$name}");
        $this->zipFile    = "{$this->pluginTempPath}.zip";
        // 效验系统函数
        $has_zip_archive = class_exists(ZipArchive::class, false);
        $this->cmd = PluginLogic::getUnzipCmd($this->zipFile, $this->extractTo);
        if (!$has_zip_archive) {
            if (!$this->cmd) {
                throw new Exception('请给php安装zip模块或者给系统安装unzip命令');
            }
            if (!function_exists('proc_open')) {
                throw new Exception('请解除proc_open函数的禁用或者给php安装zip模块');
            }
        }
        if (!function_exists('shell_exec')) {
            throw new Exception("请开启shell_exec函数");
        }
        # 已安装应用信息
        $installed_version = PluginLogic::getPluginVersion($name);
        if (!$installed_version) {
            throw new Exception('该应用未安装');
        }
        # 已安装版本
        $this->installVersion = $installed_version;
        # 获取插件信息
        $this->systemInfo = SystemInfo::info();
        $this->name       = $name;
        $this->version    = $version;
    }

    /**
     * 备份代码
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function backcode()
    {
        try {
            # 执行备份应用
            PluginLogic::backup($this->name);
            return JsonMgr::successFul('备份成功', [
                'next' => 'download'
            ]);
        } catch (\Throwable $e) {
            return JsonMgr::fail($e->getMessage());
        }
    }

    /**
     * 备份SQL
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function backsql()
    {
        return JsonMgr::successFul('备份成功', [
            'next' => 'download'
        ]);
    }

    /**
     * 下载应用包
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function download()
    {
        try {
            # 下载zip文件
            $req = new PluginRequest;
            $req->getKey();
            $req->name          = $this->name;
            $req->version       = $this->version;
            $req->saas_version  = $this->systemInfo['system_version'];
            $req->local_version = $this->installVersion;
            $cloud              = new Cloud($req);
            $data               = $cloud->send();
            # 下载应用包
            $request = new \YcOpen\CloudService\Request();
            # 通过获取下载密钥接口获得
            $request->setUrl($data->url);
            # 保存文件到指定路径
            $request->setSaveFile($this->zipFile);
            $cloud  = new Cloud($request);
            $status = $cloud->send();
            if (!$status) {
                throw new Exception('安装包下载失败');
            }
            # 下载成功
            return JsonMgr::successFul('下载成功', [
                'next' => 'delplugin'
            ]);
        } catch (\Throwable $e) {
            return JsonMgr::fail($e->getMessage());
        }
    }

    /**
     * 删除应用旧代码
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function delplugin()
    {
        try {
            if (is_dir($this->pluginPath)) {
                chdir($this->pluginPath);
                shell_exec("rm -rf {$this->pluginPath}");
            }
            return JsonMgr::successFul('删除成功', [
                'next' => 'unzip'
            ]);
        } catch (\Throwable $e) {
            return JsonMgr::fail($e->getMessage());
        }
    }

    /**
     * 解压应用包
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function unzip()
    {
        try {
            # 检测解压类是否存在
            $has_zip_archive = class_exists(ZipArchive::class, false);
            # 解压zip到plugin目录
            if ($has_zip_archive) {
                $zip = new ZipArchive;
                $zipStatus = $zip->open($this->zipFile, ZIPARCHIVE::CHECKCONS);
                if ($zipStatus !== true) {
                    $zip = null;
                }
            }
            # 解压目录
            if (!empty($zip)) {
                # 使用普通方式解压
                $zip->extractTo(base_path('/plugin/'));
                unset($zip);
            } else {
                # 使用系统级命令解压
                PluginLogic::unzipWithCmd($this->cmd);
            }
            # 解压成功
            return JsonMgr::successFul('解压成功', [
                'next' => 'reload'
            ]);
        } catch (\Throwable $e) {
            # 执行回滚
            try {
                # 更新应用失败，回滚代码
                PluginLogic::rollback($this->name);
            } catch (\Throwable $e) {
                return JsonMgr::fail("更新失败，回滚失败：{$e->getMessage()}");
            }
            return JsonMgr::fail($e->getMessage());
        }
    }

    /**
     * 软重启框架
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function reload()
    {
        if (function_exists('posix_kill')) {
            try {
                console_log("停止子进程---执行成功");
                // 重启子进程
                posix_kill(posix_getppid(), SIGUSR1);
            } catch (\Throwable $e) {
                p("停止框架失败---{$e->getMessage()}");
            }
        } else {
            Timer::add(1, function () {
                Worker::stopAll();
            });
            echo "重启子进程---执行成功" . PHP_EOL;
        }
        return JsonMgr::successFul('重启成功', [
            'next' => 'ping'
        ]);
    }

    /**
     * 检测服务
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function ping()
    {
        return JsonMgr::successFul('重启成功', [
            'next' => 'updateData'
        ]);
    }

    /**
     * 更新数据同步
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function updateData()
    {
        try {
            $context = null;
            $install_class = "\\plugin\\{$this->name}\\api\\Install";
            # 执行beforeUpdate
            if (class_exists($install_class) && method_exists($install_class, 'beforeUpdate')) {
                $context = call_user_func([$install_class, 'beforeUpdate'], $this->installVersion, $this->version);
            }
            # 检测composer
            ComposerMgr::composerMergePlugin($this->name);
            # 删除压缩包
            unlink($this->zipFile);
            # 执行update更新
            if (class_exists($install_class) && method_exists($install_class, 'update')) {
                call_user_func([$install_class, 'update'], $this->installVersion, $this->version, $context);
            }
            # 更新成功
            return JsonMgr::successFul('应用更新成功，请等待', [
                'next' => 'success'
            ]);
        } catch (\Throwable $e) {
            # 执行回滚
            try {
                # 更新应用失败，回滚代码
                PluginLogic::rollback($this->name);
            } catch (\Throwable $e) {
                return JsonMgr::fail("更新失败，回滚失败：{$e->getMessage()}");
            }
            return JsonMgr::fail($e->getMessage());
        }
    }
}