<?php

namespace app\admin\logic;
use app\admin\service\kfcloud\Updated;
use app\utils\Utils;
use Exception;
use process\Monitor;
use think\facade\Db;
use ZipArchive;

/**
 * 框架核心逻辑
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class AppCoreLogic
{
    /**
     * 需要打包备份的目录或文件
     * @var array
     */
    private static $backupList = [
        'app',
        'view',
        'vendor',
        'composer.json',
        'composer.lock',
    ];

    /**
     * 获取更新框架KEY
     * @param string $version
     * @throws \Exception
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getDownKey(string $version):string
    {
        $response = Updated::getSystemDownKey($version)->array();
        if (!$response) {
            throw new Exception('获取更新KEY失败');
        }
        if (!isset($response['code']) && !isset($response['data'])) {
            throw new Exception('请求更新失败');
        }
        if ($response['code'] !== 200) {
            throw new Exception($response['msg'],$response['code']);
        }
        if (empty($response['data']['key'])) {
            throw new Exception('更新KEY错误');
        }
        return $response['data']['key'];
    }

    /**
     * 下载文件流储存至包
     * @param string $key
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function downPack(string $key)
    {
        $packRes = Updated::getZip($key);
        $pack    = $packRes->array();
        if (is_array($pack) && isset($pack['code'])) {
            throw new Exception($pack['msg'],$pack['code']);
        }
        // 写入包体
        $zip_file = runtime_path('/core/updated.zip');
        if (!is_dir(dirname($zip_file))) {
            mkdir(dirname($zip_file), 0755, true);
        }
        file_put_contents($zip_file, $packRes->body());
    }

    /**
     * 执行更新
     * @param string $version
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function update(string $version)
    {
        # 备份框架核心
        self::backup();
        # 备份成功，开始走更新
        $zip_file = runtime_path('/core/updated.zip');
        # 效验系统函数
        $has_zip_archive = class_exists(ZipArchive::class, false);
        if (!$has_zip_archive) {
            $cmd = PluginLogic::getUnzipCmd($zip_file, base_path());
            if (!$cmd) {
                throw new Exception('请给php安装zip模块或者给系统安装unzip命令');
            }
            if (!function_exists('proc_open')) {
                throw new Exception('请解除proc_open函数的禁用或者给php安装zip模块');
            }
        }
        $monitor_support_pause = method_exists(Monitor::class, 'pause');
        if ($monitor_support_pause) {
            Monitor::pause();
        }
        # 开始执行更新步骤
        Db::startTrans();
        try {
            # 解压zip到根目录
            if ($has_zip_archive) {
                $zip = new ZipArchive;
                $zip->open($zip_file);
            }
            if (!empty($zip)) {
                $zip->extractTo(base_path());
                echo "框架代码更新成功...\n";
                unset($zip);
            } else {
                PluginLogic::unzipWithCmd($cmd);
            }
            // 更新类路径
            $install_class = "app\\Install";
            if (class_exists($install_class)) {
                // 执行更新前置
                $context       = null;
                if (method_exists($install_class, 'beforeUpdate')) {
                    $context = call_user_func([$install_class, 'beforeUpdate'], $version);
                    echo "框架前置更新成功...\n";
                }
                unlink($zip_file);
                // 执行update更新
                if (method_exists($install_class, 'update')) {
                    call_user_func([$install_class, 'update'], $version, $context);
                    echo "执行更新安装成功...\n";
                }
                // 删除更新类
                unlink(app_path('/Install.php'));
            }
            # 提交事务
            Db::commit();
        }catch(\Throwable $e){
            # 回滚事务
            Db::rollback();
            console_log("更新失败，回滚事务：{$e->getMessage()}");
            try {
                # 回滚失败，删除更新包
                self::rollback();
            } catch (\Throwable $e) {
                throw $e;
            }
        } finally {
            if ($monitor_support_pause) {
                Monitor::resume();
            }
        }
        // 重启框架
        Utils::reloadWebman();
        echo "更新重启成功...\n";
    }
    
    /**
     * 备份框架目录及文件
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private static function backup()
    {
        # 打包至目标压缩包
        $zipFile = runtime_path('/core/backup.zip');
        if (!is_dir(dirname($zipFile))) {
            mkdir(dirname($zipFile), 0755, true);
        }
        # 打开压缩包资源
        $zip      = new ZipArchive;
        $zipStatus = $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if (!$zipStatus) {
            throw new Exception('打开备份源包地址失败');
        }
        # 备份框架核心
        foreach (self::$backupList as $item) {
            if (is_dir(base_path($item))) {
                $zip->addEmptyDir($item);
                $files = scanDir(base_path($item));
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        $path = $item . DIRECTORY_SEPARATOR . $file;
                        if (is_dir(base_path($path))) {
                            $zip->addEmptyDir($path);
                            self::addFileToZip(base_path($path), $zip, $path);
                        } else {
                            $zip->addFile(base_path($path), $path);
                        }
                    }
                }
            } else {
                $zip->addFile(base_path($item), $item);
            }
        }
        # 关闭打包资源
        $zip->close();
        console_log("框架备份成功...");
    }

    /**
     * 解压还原框架
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private static function rollback()
    {
        $zip_file = runtime_path("/core/backup.zip");
        $extract_to = base_path();
        if (!is_file($zip_file)) {
            throw new Exception("备份核心包文件不存在");
        }
        if (!is_dir($extract_to)) {
            throw new Exception("框架根目录错误");
        }
        # 获取系统支持的解压命令
        $cmd = PluginLogic::getUnzipCmd($zip_file, $extract_to);
        if (!$cmd) {
            throw new Exception("系统不支持解压命令");
        }
        # 使用解压命令解压
        PluginLogic::unzipWithCmd($cmd);
        # 删除源代码包
        unlink($zip_file);
        # 回滚还原框架
        console_log("框架回滚成功...");
    }

    
    /**
     * 遍历打包文件
     * @param string $sourcePath
     * @param \ZipArchive $zip
     * @param string $name
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private static function addFileToZip(string $sourcePath,ZipArchive $zip, string $name)
    {
        $files = scandir($sourcePath);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $path = $sourcePath . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    $zip->addEmptyDir($name . DIRECTORY_SEPARATOR . $file);
                    self::addFileToZip($path, $zip, $name . DIRECTORY_SEPARATOR . $file);
                }
                else {
                    $zip->addFile($path, $name . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
    }
    
}
