<?php

use app\admin\service\kfcloud\CloudService;
use app\admin\service\kfcloud\SystemInfo;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\SiteRequest;
use YcOpen\CloudService\Request\SystemUpdateRequest;

class Helpers
{
    /**
     * 安装配置文件
     * @param array $post
     * @throws Exception
     * @return bool|int
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    public static function installEnv(array $post): int|false
    {
        // 获取框架服务设置
        $serverData = isset($post['serverData']) ? $post['serverData'] : null;
        if (!$serverData) {
            throw new Exception('框架启动端口设置错误');
        }
        $server_port = isset($serverData['server_port']) ? $serverData['server_port'] : '';
        // 数据库获取参数
        $database = isset($post['database']) ? $post['database'] : null;
        $site = isset($post['site']) ? $post['site'] : null;
        if (!$database) {
            throw new Exception('获取安装数据失败');
        }
        $type = isset($database['type']) ? $database['type'] : '';
        $host = isset($database['host']) ? $database['host'] : '';
        $databaseName = isset($database['database']) ? trim($database['database']) : '';
        $port = isset($database['port']) ? $database['port'] : '';
        $user = isset($database['username']) ? trim($database['username']) : '';
        $password = isset($database['password']) ? trim($database['password']) : '';
        $charset = isset($database['charset']) ? trim($database['charset']) : '';
        $prefix = isset($database['prefix']) ? trim($database['prefix']) : '';
        $web_url = isset($site['web_url']) ? trim($site['web_url']) : '';

        // 拼接配置文件路径
        $envTplPath = KF_INSTALL_PATH . "/data/env.tpl";
        $envPath = ROOT_PATH . "/.env";

        // 读取配置文件
        $envConfig = file_get_contents($envTplPath);

        // 云服务

        $req = new SiteRequest;
        $req->install();
        $req->title = $site['web_name'];
        $req->domain = $site['web_url'];
        $cloud = new Cloud($req);
        $cloud->send();

        // 替换配置文件参数
        $str1 = [
            "{TYPE}",
            "{HOSTNAME}",
            "{DATABASE}",
            "{USERNAME}",
            "{PASSWORD}",
            "{HOSTPORT}",
            "{CHARSET}",
            "{CHARSET_CI}",
            "{PREFIX}",
            "{UPLOAD_PUBLIC_URL}",
            "{SERVER_PORT}"
        ];
        $str2 = [
            $type,
            $host,
            $databaseName,
            $user,
            $password,
            $port,
            'utf8mb4',
            'utf8mb4_general_ci',
            $prefix,
            $web_url,
            $server_port,
        ];
        $envConfig = str_replace($str1, $str2, $envConfig);

        // 写入配置文件
        return file_put_contents($envPath, $envConfig);
    }

    /**
     * 替换SQL文件内容
     *
     * @param string $filename
     * @param string $prefix
     * @return string
     */
    public static function strReplace(string $sqlPath, string $prefix): string
    {
        $filename = basename($sqlPath);
        if (!file_exists($sqlPath)) {
            throw new Exception("找不到{$filename}数据文件");
        }
        // 替换SQL模板文件内容
        $_sql = file_get_contents($sqlPath);
        // 替换前缀
        $_sql = str_replace("php_", $prefix, $_sql);
        return $_sql;
    }
}