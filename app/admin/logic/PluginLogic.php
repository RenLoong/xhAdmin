<?php
namespace app\admin\logic;
use app\admin\service\kfcloud\CloudService;
use Exception;

class PluginLogic
{
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
    public static function downloadZipFile($key, $file)
    {
        $data = CloudService::getZip($key);
        $response = $data->array();
        if (is_array($response) && isset($response['code'])) {
            throw new Exception($response['msg']);
        }
        $zip_content = $data->body();
        file_put_contents($file, $zip_content);
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
        }
        else if ($cmd = self::findCmd('7z')) {
            $cmd = "$cmd x -bb0 -y $zip_file -o$extract_to";
        }
        else if ($cmd = self::findCmd('7zz')) {
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
            $dirs       = [];
            foreach ($searchPath as $path) {
                if (@is_dir($path)) {
                    $dirs[] = $path;
                }
                else {
                    if (basename($path) == $name && @is_executable($path)) {
                        return $path;
                    }
                }
            }
        }
        else {
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
        if (!is_file($file = base_path() . "/plugin/{$name}/config/app.php")) {
            return null;
        }
        $config = include $file;
        return $config['version'] ?? null;
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
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}