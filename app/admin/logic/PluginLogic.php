<?php
namespace app\admin\logic;

use Exception;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request;
use ZipArchive;

class PluginLogic
{
    /**
     * 回滚插件备份
     * @param string $name
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function rollback(string $name)
    {
        $zip_file = base_path("/runtime/backupPlugin/{$name}.zip");
        $extract_to = base_path("/plugin");
        if (!is_file($zip_file)) {
            throw new Exception("备份文件不存在");
        }
        if (!is_dir($extract_to)) {
            throw new Exception("插件目录不存在");
        }
        $cmd = self::getUnzipCmd($zip_file, $extract_to);
        if (!$cmd) {
            throw new Exception("系统不支持解压命令");
        }
        self::unzipWithCmd($cmd);
        # 删除源代码包
        unlink($zip_file);
    }

    /**
     * 备份插件
     * @param mixed $name
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function backup(string $name)
    {
        $zip_file = base_path("/runtime/backupPlugin/{$name}.zip");
        if (!is_dir(dirname($zip_file))) {
            mkdir(dirname($zip_file), 0755, true);
        }
        $zip = new ZipArchive;
        $openStatus = $zip->open($zip_file, ZipArchive::CREATE);
        if ($openStatus !== true) {
            throw new Exception('源代码备份失败');
        }
        $sourcePath = base_path("/plugin/{$name}");
        self::addFileToZip($sourcePath, $zip, $name);
        $zip->close();
    }

    /**
     * 扫描插件目录并备份
     * @param string $sourcePath
     * @param \ZipArchive $zip
     * @param string $name
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
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

    /**
     * 使用解压命令解压
     * @param mixed $cmd
     * @throws Exception
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function unzipWithCmd($cmd)
    {
        $desc = [
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
     * 下载ZIP
     * @param mixed $key
     * @param mixed $file
     * @throws Exception
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function downloadZipFile($url, $file)
    {
        $req = new Request();
        $req->setUrl($url);
        $req->setSaveFile($file);
        $cloud = new Cloud($req);
        $cloud->send();
    }

    /**
     * 获取系统支持的解压命令
     * @param $zip_file
     * @param $extract_to
     * @return mixed|string|null
     */
    public static function getUnzipCmd($zip_file, $extract_to)
    {
        if ($cmd = self::findCmd('unzip')) {
            $cmd = "$cmd -o -qq $zip_file -d $extract_to";
        } else if ($cmd = self::findCmd('7z')) {
            $cmd = "$cmd x -bb0 -y $zip_file -o$extract_to";
        } else if ($cmd = self::findCmd('7zz')) {
            $cmd = "$cmd x -bb0 -y $zip_file -o$extract_to";
        }
        return $cmd;
    }

    /**
     * 查找系统命令
     * @param string $name
     * @param string|null $default
     * @param array $extraDirs
     * @return mixed|string|null
     */
    public static function findCmd(string $name, string $default = null, array $extraDirs = [])
    {
        if (ini_get('open_basedir')) {
            $searchPath = array_merge(explode(PATH_SEPARATOR, ini_get('open_basedir')), $extraDirs);
            $dirs = [];
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
            $pathExt = getenv('PATHEXT');
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

    /**
     * 获取本地插件版本
     * @param mixed $name
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function getPluginVersion($name)
    {
        $json = base_path("/plugin/{$name}/version.json");
        if (!is_file($json)) {
            return 1;
        }
        $config = json_decode(file_get_contents($json), true);
        return isset($config['version']) ? $config['version'] : 1;
    }

    /**
     * 获取应用版本数据
     * @param mixed $name
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getPluginVersionData($name)
    {
        $json = base_path("/plugin/{$name}/version.json");
        if (!is_file($json)) {
            return [
                'version' => 1,
                'version_name' => '1.0.0'
            ];
        }
        $config = json_decode(file_get_contents($json), true);
        # 返回数据
        return $config ? $config : ['version' => 1, 'version_name' => '1.0.0'];
    }

    /**
     * 获取已安装的插件列表
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function getLocalPlugins(): array
    {
        clearstatcache();
        $installed = [];
        $plugin_names = array_diff(scandir(base_path('/plugin/')), array('.', '..')) ?: [];
        foreach ($plugin_names as $plugin_name) {
            if (is_dir(base_path("/plugin/{$plugin_name}")) && $version = self::getPluginVersion($plugin_name)) {
                $installed[$plugin_name] = $version;
            }
        }
        return $installed;
    }

    /**
     * 删除目录
     * @param mixed $src
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function rmDir($src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    self::rmDir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}