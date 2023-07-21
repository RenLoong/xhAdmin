<?php

namespace app\common\manager;

use Exception;

/**
 * 执行系统命令ZIP管理器
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class SystemZipCmdMgr
{
    /**
     * 使用解压命令解压
     * @param mixed $zipFile
     * @param mixed $extractTo
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function unzipWith($zipFile, $extractTo)
    {
        # 获取系统级解压命令
        $cmd     = self::getUnzipCmd($zipFile, $extractTo);
        $desc    = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"],
        ];
        $handler = proc_open($cmd, $desc, $pipes);
        if (!is_resource($handler)) {
            throw new Exception("解压zip时出错:proc_open调用失败");
        }
        $err = fread($pipes[2], 1024);
        fclose($pipes[2]);
        proc_close($handler);
        if ($err) {
            throw new Exception("解压zip时出错:$err");
        }
    }

    /**
     * 获取系统支持的解压命令
     * @param $zip_file
     * @param $extract_to
     * @return mixed|string|null
     */
    public static function getUnzipCmd($zipFile, $extractTo)
    {
        if ($cmd = self::findCmd('unzip')) {
            $cmd = "{$cmd} -o -qq {$zipFile} -d {$extractTo}";
        } else if ($cmd = self::findCmd('7z')) {
            $cmd = "{$cmd} x -bb0 -y {$zipFile} -o {$extractTo}";
        } else if ($cmd = self::findCmd('7zz')) {
            $cmd = "{$cmd} x -bb0 -y {$zipFile} -o {$extractTo}";
        }
        return $cmd;
    }

    /**
     * 使用系统命令进行打包
     * @param mixed $zipFile 打包至某个目录
     * @param mixed $extractTo 打包的目标目录
     * @param mixed $ignoreFiles 需要忽略的目录或者文件
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function zipBuild($zipFile, $extractTo, $ignoreFiles = [])
    {
        # 切换工作区为站点根目录
        chdir($extractTo);
        # 获取系统级打包命令
        $cmd     = self::getZipBuildCmd($zipFile, '*', $ignoreFiles);
        $desc    = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"],
        ];
        $handler = proc_open($cmd, $desc, $pipes);
        if (!is_resource($handler)) {
            throw new Exception("打包zip时出错:proc_open调用失败");
        }
        $err = fread($pipes[2], 1024);
        fclose($pipes[2]);
        proc_close($handler);
        if ($err) {
            throw new Exception("打包zip时出错:$err");
        }
    }

    /**
     * 获取系统支持的ZIP打包命令
     * @param mixed $zipFile 打包至某个目录
     * @param mixed $extractTo 打包的目标目录
     * @param mixed $ignoreFiles 需要忽略的目录或者文件
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getZipBuildCmd($zipFile, $extractTo, $ignoreFiles = [])
    {
        if ($cmd = self::findCmd('zip')) {
            # 是否需要忽略文件
            if (!empty($ignoreFiles)) {
                $ignoreFiles = implode(' ', $ignoreFiles);
                $extractTo   = "{$extractTo} -x {$ignoreFiles}";
            }
            # zip
            $cmd = "{$cmd} -o -qq -r {$zipFile} {$extractTo}";
        } else if ($cmd = self::findCmd('tar')) {
            $option = '';
            if (!empty($ignoreFiles)) {
                $ignoreFiles = array_map(function ($item) {
                    return "--exclude=\"{$item}\"";
                }, $ignoreFiles);
                $ignoreFiles = implode(' ', $ignoreFiles);
                $option      = " {$ignoreFiles}";
            }
            # 系统级命令
            $cmd = "{$cmd}{$option} -cf {$zipFile} {$extractTo}";
        } else if ($cmd = self::findCmd('7z')) {
            if (!empty($ignoreFiles)) {
                $ignoreFiles = array_map(function ($item) {
                    return "'-xr!{$item}'";
                }, $ignoreFiles);
                $ignoreFiles = implode(' ', $ignoreFiles);
                $extractTo   = "{$extractTo} {$ignoreFiles}";
            }
            # 7z
            $cmd = "{$cmd} a {$zipFile} {$extractTo}";
        } else if ($cmd = self::findCmd('7zz')) {
            if (!empty($ignoreFiles)) {
                $ignoreFiles = array_map(function ($item) {
                    return "'-xr!{$item}'";
                }, $ignoreFiles);
                $ignoreFiles = implode(' ', $ignoreFiles);
                $extractTo   = "{$extractTo} {$ignoreFiles}";
            }
            # 7zz
            $cmd = "{$cmd} a {$zipFile} {$extractTo}";
        }
        return $cmd;
    }

    /**
     * 查找系统命令
     * @param string $name
     * @param string $default
     * @param array $extraDirs
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function findCmd(string $name, string $default = null, array $extraDirs = [])
    {
        if (ini_get('open_basedir')) {
            $searchPath = array_merge(explode(PATH_SEPARATOR, ini_get('open_basedir')), $extraDirs);
            $dirs       = [];
            foreach ($searchPath as $path) {
                if (@is_dir($path)) {
                    $dirs[] = $path;
                } else {
                    if (basename($path) == $name && @is_executable($path)) {
                        return $path;
                    }
                }
            }
        } else {
            $dirs = array_merge(
                explode(PATH_SEPARATOR, getenv('PATH') ?: getenv('Path')),
                $extraDirs
            );
        }
        $suffixes = [''];
        if ('\\' === DIRECTORY_SEPARATOR) {
            $pathExt  = getenv('PATHEXT');
            $suffixes = array_merge($pathExt ? explode(PATH_SEPARATOR, $pathExt) : ['.exe', '.bat', '.cmd', '.com'], $suffixes);
        }
        foreach ($suffixes as $suffix) {
            foreach ($dirs as $dir) {
                if (@is_file($file = $dir . DIRECTORY_SEPARATOR . $name . $suffix) && ('\\' === DIRECTORY_SEPARATOR || @is_executable($file))) {
                    return $file;
                }
            }
        }
        return $default;
    }
}