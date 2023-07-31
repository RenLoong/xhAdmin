<?php
namespace app\common\manager;

use Exception;
use ZipArchive;

/**
 * 原生PHP-ZipArchive打包管理器
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class PhpZipArchiveMgr
{
    /**
     * 是否支持ZipArchive扩展
     * @var bool
     */
    protected $hasZipArchive = false;

    /**
     * ZipArchive扩展
     * @var ZipArchive
     */
    protected $zipCls = null;

    /**
     * 构造函数
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function __construct()
    {
        $has_zip_archive = class_exists(ZipArchive::class, false);
        if (!$has_zip_archive) {
            throw new Exception('请给php安装zip模块');
        }
        $this->zipCls = new ZipArchive;
    }

    /**
     * 打包某目录下所有文件
     * @param string $zipFilePath 打包至目标压缩包
     * @param string $extractTo 打包目标路径
     * @param array $ignoreFiles 需要忽略的绝对目录路径或者文件（可选）
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function build(string $zipFilePath, string $extractTo, array $ignoreFiles = [])
    {
        $zip        = $this->zipCls;
        $openStatus = $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openStatus !== true) {
            throw new Exception('打包失败');
        }
        # 执行递归打包
        self::addFileToZip($zip, $extractTo, '/', $ignoreFiles);
        # 关闭资源
        $zip->close();
    }

    /**
     * 打包所需文件
     * @param string $zipFilePath
     * @param array $files
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function buildFiles(string $zipFilePath,string $extractTo, array $files)
    {
        $zip        = $this->zipCls;
        $openStatus = $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openStatus !== true) {
            throw new Exception('打包失败');
        }
        # 执行递归打包
        foreach ($files as $file) {
            $filePath = "{$extractTo}/{$file}";
            if (is_file($filePath)) {
                $zip->addFile($filePath, $file);
            }else{
                $this->addFileToZip($zip, $filePath, "{$file}/");
            }
        }
        # 关闭资源
        $zip->close();
    }

    /**
     * 扫描目录并添加文件至压缩包
     * @param \ZipArchive $zip
     * @param string $sourcePath
     * @param string $zipPath
     * @param array $ignoreFiles
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function addFileToZip(ZipArchive $zip, string $sourcePath, string $zipPath = '/', array $ignoreFiles = [])
    {
        $files = scandir($sourcePath);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $path = $sourcePath . DIRECTORY_SEPARATOR . $file;
                if (!in_array($path, $ignoreFiles)) {
                    if (is_dir($path)) {
                        $zip->addEmptyDir($zipPath . $file);
                        $this->addFileToZip($zip, $path, $zipPath . $file . DIRECTORY_SEPARATOR);
                    } else {
                        $zip->addFile($path, $zipPath . $file);
                    }
                }
            }
        }
    }

    /**
     * 解压目标压缩包至目录
     * @param string $zipFilePath 压缩包路径
     * @param string $tarGetPath 解压至目标路径
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function unzip(string $zipFilePath, string $tarGetPath)
    {
        # 检测压缩包是否存在
        if (!is_file($zipFilePath)) {
            throw new Exception('压缩包不存在');
        }
        # 检测目录不存在则创建
        if (!is_dir($tarGetPath)) {
            mkdir($tarGetPath, 0777, true);
        }
        $zip        = $this->zipCls;
        $openStatus = $zip->open($zipFilePath);
        if ($openStatus !== true) {
            throw new Exception('解压失败');
        }
        # 解压至目标目录
        $zip->extractTo($tarGetPath);
        # 关闭资源
        $zip->close();
    }
}