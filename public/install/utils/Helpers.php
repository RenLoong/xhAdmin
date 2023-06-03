<?php

use app\admin\service\kfcloud\CloudService;

class Helpers
{
    /**
     * 安装nginx配置文件
     *
     * @param  string $server_name
     * @param  array  $data
     * @return void
     */
    public static function installNginx(string $server_name,array $data)
    {
        $siteDirName = basename(ROOT_PATH);
        $configPath = "/www/server/panel/vhost/nginx/{$siteDirName}.conf";
        $btPanel = new BtPanel($data['btData']['panel_url'], $data['btData']['panel_key']);
        $response = $btPanel->getFileBody(['path'=> $configPath]);
        if (!isset($response['data'])) {
            throw new Exception('获取Ningx站点配置错误');
        }
        $nginxConfig = $response['data'];
        # 设置nginx---upstream
        $nginxUpstream = file_get_contents(KF_INSTALL_PATH. '/data/nginx/nginx_upstream.tpl');
        $str1 = ['{SERVER_NAME}', '{SERVER_PORT}'];
        $str2 = [$server_name,$data['serverData']['server_port']];
        $nginxUpstream = str_replace($str1,$str2, $nginxUpstream);
        $newNginxConfig = "{$nginxUpstream}\n{$nginxConfig}";
        # 设置nginx---server
        $nginxUpstream = file_get_contents(KF_INSTALL_PATH . '/data/nginx/nginx_server.tpl');
        $str1 = ['{SERVER_NAME}'];
        $str2 = [$server_name];
        $nginxUpstream = str_replace($str1,$str2, $nginxUpstream);
        $sslEnd = "#SSL-END\n\n\n\n\n{$nginxUpstream}\n\n\n";
        $newNginxConfig = str_replace("#SSL-END", $sslEnd, $newNginxConfig);
        // 保存nginx配置
        $data = [
            'path'      => $configPath,
            'encoding'  => 'utf-8',
            'data'      => $newNginxConfig
        ];
        $response = $btPanel->SaveFileBody($data);
        if (!isset($response['status']) || !$response['status']) {
            throw new Exception('保存Nginx站点配置失败');
        }
    }

    /**
     * 安装守护进程配置
     *
     * @param  string $server_name
     * @param  array  $data
     * @return void
     */
    public static function installSupervisor(string $server_name,array $data)
    {
        $btPanel = new BtPanel($data['btData']['panel_url'], $data['btData']['panel_key']);
        $queryData = [
            'pjname' => $server_name,
            'user' => 'root',
            'path' => ROOT_PATH . '/',
            'command' => 'php webman start',
            'numprocs' => 1,
        ];
        $response = $btPanel->saveSupervisor($queryData);
        if (!isset($response['status']) || !$response['status']) {
            throw new Exception('安装守护进程失败');
        }
    }

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
        $type     = isset($database['type']) ? $database['type'] : '';
        $host     = isset($database['host']) ? $database['host'] : '';
        $databaseName = isset($database['database']) ? trim($database['database']) : '';
        $port     = isset($database['port']) ? $database['port'] : '';
        $user     = isset($database['username']) ? trim($database['username']) : '';
        $password = isset($database['password']) ? trim($database['password']) : '';
        $charset  = isset($database['charset']) ? trim($database['charset']) : '';
        $prefix   = isset($database['prefix']) ? trim($database['prefix']) : '';
        $web_url   = isset($site['web_url']) ? trim($site['web_url']) : '';

        // 拼接配置文件路径
        $envTplPath = KF_INSTALL_PATH . "/data/env.tpl";
        $envPath    = ROOT_PATH . "/.env";

        // 读取配置文件
        $envConfig = file_get_contents($envTplPath);

        // 云服务
        $response = CloudService::installSite(
            $site['web_name'],
            $site['web_url']
        )
        ->array();
        if (!$response) {
            throw new Exception('安装云站点失败', 404);
        }
        if (!isset($response['code'])) {
            throw new Exception('安装云站点失败！', 404);
        }
        if ($response['code'] !== 200) {
            throw new Exception($response['msg'], $response['code']);
        }

        // 替换配置文件参数
        $str1      = [
            "{TYPE}",
            "{HOSTNAME}",
            "{DATABASE}",
            "{USERNAME}",
            "{PASSWORD}",
            "{HOSTPORT}",
            "{CHARSET}",
            "{PREFIX}",
            "{UPLOAD_PUBLIC_URL}",
            "{SERVER_PORT}"
        ];
        $str2      = [
            $type,
            $host,
            $databaseName,
            $user,
            $password,
            $port,
            $charset,
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