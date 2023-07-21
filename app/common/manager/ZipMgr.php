<?php
namespace app\common\manager;
use Exception;

class ZipMgr
{
    /**
     * 执行打包
     * @param string $zipFilePath 打包文件路径
     * @param string $extractTo 打包目标路径
     * @param array $ignoreFiles 需要忽略的目录或者文件
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function build(string $zipFilePath,string $extractTo,array $ignoreFiles = [])
    {
        $has_zip_archive = class_exists(ZipArchive::class, false);
        # 检测是否支持打包
        if (!SystemZipCmdMgr::getZipBuildCmd($zipFilePath, $extractTo) && !$has_zip_archive) {
            throw new Exception('请给php安装zip模块或者给系统安装zip命令');
        }
        # 开始执行打包
        if (SystemZipCmdMgr::getZipBuildCmd($zipFilePath, $extractTo)) {
            # 系统级命令打包
            if (!function_exists('proc_open')) {
                throw new Exception('请解除proc_open函数的禁用');
            }
            # 开始执行系统打包
            SystemZipCmdMgr::zipBuild($zipFilePath, $extractTo,$ignoreFiles);
        } else {
            # 使用ZipArchive扩展打包
            console_log("开始使用ZipArchive扩展打包");
        }
    }
    
    /**
     * 解压
     * @param string $zipFilePath 压缩文件路径
     * @param string $extractTo 解压目标路径
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function unzip(string $zipFilePath,string $extractTo)
    {
        $has_zip_archive = class_exists(ZipArchive::class, false);
        # 检测是否支持解压命令或者扩展
        if (!SystemZipCmdMgr::getUnzipCmd($zipFilePath, $extractTo) && !$has_zip_archive) {
            throw new Exception('请给php安装zip模块或者给系统安装unzip命令');
        }
        # 检测目标目录不存在则创建
        if (!is_dir($extractTo)) {
            mkdir($extractTo, 0755, true);
        }
        # 开始执行打包
        if (SystemZipCmdMgr::getUnzipCmd($zipFilePath, $extractTo)) {
            # 系统级命令打包
            if (!function_exists('proc_open')) {
                throw new Exception('请解除proc_open函数的禁用');
            }
            # 开始执行系统打包
            SystemZipCmdMgr::unzipWith($zipFilePath, $extractTo);
        } else {
            # 使用ZipArchive扩展解压
            console_log("开始使用ZipArchive扩展解压");
        }
    }
}