<?php
namespace app\common\service;
use Exception;
use support\Request;
use ZipArchive;

class SystemUpdateService
{
    /**
     * 本地版本信息
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $versionData = [];

    /**
     * 是否支持ZIPArchive
     * @var 
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $hasZipArchive = false;

    /**
     * 执行系统命令解压
     * @var 
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $cmd = null;

    /**
     * 临时ZIP文件路径
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $tempZipFilePath = null;

    /**
     * 备份ZIP路径
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $backupPath = null;

    /**
     * 解压的目标路径
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $targetPath = null;

    
    /**
     * 打包时忽略文件或目录列表
     * 删除时忽略以下目录或文件
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static $ignoreList = [
        '/.env',
        '/public',
        '/plugin',
    ];

    /**
     * 备份需要覆盖的文件
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static $backCoverList = [
        '/config/plugin',
        '/config/redis.php',
    ];

    /**
     * 构造函数
     * @param array $versionData
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct(array $versionData)
    {
        $this->versionData = $versionData;
        # 设置路径
        $this->tempZipFilePath = runtime_path("/core/kfadmin.zip");
        $this->targetPath = base_path();
        $this->backupPath = runtime_path("/core/backup.zip");
        # 效验系统函数
        $this->hasZipArchive = class_exists(ZipArchive::class, false);
        $this->cmd = PluginLogic::getUnzipCmd($this->tempZipFilePath, $this->targetPath);
        if (!$this->hasZipArchive) {
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
    }

    /**
     * 备份代码
     * @param \support\Request $request
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function backCode(Request $request)
    {
        # 打包至目标压缩包
        if (!is_dir(dirname($this->backupPath))) {
            mkdir(dirname($this->backupPath), 0755, true);
        }
        # 支持ZipArchive
        if ($this->hasZipArchive) {
            $zip = new ZipArchive;
            $zipStatus = $zip->open($this->backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            if (!$zipStatus) {
                throw new Exception('打开备份源包地址失败');
            }
            $basePathFiles = scandir($this->targetPath);
            p($basePathFiles);
            # 备份框架核心
            // foreach (self::$backupList as $item) {
            //     if (is_dir(base_path($item))) {
            //         $zip->addEmptyDir($item);
            //         $files = scanDir(base_path($item));
            //         foreach ($files as $file) {
            //             if ($file != "." && $file != "..") {
            //                 $path = $item . DIRECTORY_SEPARATOR . $file;
            //                 if (is_dir(base_path($path))) {
            //                     $zip->addEmptyDir($path);
            //                     self::addFileToZip(base_path($path), $zip, $path);
            //                 } else {
            //                     $zip->addFile(base_path($path), $path);
            //                 }
            //             }
            //         }
            //     } else {
            //         $zip->addFile(base_path($item), $item);
            //     }
            // }
            // # 关闭打包资源
            // $zip->close();
            // console_log("框架备份成功...");
        }
        # 检测如果都不支持zip模块和unzip命令则抛出异常
        if (!$this->hasZipArchive && !$this->cmd) {
            throw new Exception('请给php安装zip模块或者给系统安装unzip命令');
        }
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
    private static function addFileToZip(string $sourcePath, ZipArchive $zip, string $name)
    {
        $files = scandir($sourcePath);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $path = $sourcePath . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    $zip->addEmptyDir($name . DIRECTORY_SEPARATOR . $file);
                    self::addFileToZip($path, $zip, $name . DIRECTORY_SEPARATOR . $file);
                } else {
                    $zip->addFile($path, $name . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
    }

    public function backSql(Request $request)
    {
    }
    public function download(Request $request)
    {
    }
    public function delplugin(Request $request)
    {
    }
    public function unzip(Request $request)
    {
    }
    public function ping(Request $request)
    {
    }
    public function updateData(Request $request)
    {
    }
}