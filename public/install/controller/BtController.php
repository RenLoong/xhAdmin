<?php

use app\common\utils\Password;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\SystemUpdateRequest;

class BtController
{
    /**
     * 构造函数
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct()
    {
        if (!empty($_POST['database'])) {
            $config = $_POST['database'];
            Db::connect($config);
        }
    }

    /**
     * 安装前检测
     * @throws \Exception
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function install()
    {
        # 获取数据
        $step  = isset($_GET['step']) ? $_GET['step'] : '';
        $total = isset($_GET['total']) ? intval($_GET['total']) : 0;
        $post  = $_POST;
        # 宝塔相关数据
        if (!isset($post['btData']))
            return Json::fail('缺少宝塔面板数据');
        $btData      = isset($post['btData']) ? $post['btData'] : null;
        $panel_ssl   = isset($btData['panel_ssl']) ? (bool) $btData['panel_ssl'] : false;
        $panelLogic  = new BtPanelLogic($btData['panel_port'], $btData['panel_key'], $panel_ssl);
        $server_name = str_replace('.', '_', basename(ROOT_PATH));
        # 执行安装步骤
        switch ($step) {
            # 安装数据库结构
            case 'structure':
                $database = isset($post['database']) ? $post['database'] : [];
                if (!$database) {
                    return Json::fail('获取安装数据库配置失败');
                }
                # 数据库连接
                try {
                    # 获取SQL文件树
                    $sqlTrees = Dir::tree(KF_INSTALL_PATH . '/data/sql');
                    if ($total >= count($sqlTrees)) {
                        return Json::json('安装数据库结构完成...', 200, [
                            'next' => 'database'
                        ]);
                    }
                    $sqlItem = isset($sqlTrees[$total]) ? $sqlTrees[$total] : null;
                    if (!$sqlItem) {
                        throw new Exception('安装数据库结构失败');
                    }
                    # 替换SQL
                    $sql = Helpers::strReplace($sqlItem['path'], $database['prefix']);
                    # 执行SQL
                    Db::exceSQL($sql);
                    # 获取安装名称
                    $installName = str_replace(['.sql', 'php_','yc_'], '', $sqlItem['filename']);
                    # 返回成功
                    return Json::json("安装 【{$installName}】 数据表成功", 200, [
                        'next' => 'structure',
                        'total' => $total + 1
                    ]);
                } catch (\Throwable $e) {
                    return Json::failFul($e->getMessage(), 404);
                }
            # 写入数据库数据
            case 'database':
                $database = isset($post['database']) ? $post['database'] : null;
                $site = isset($post['site']) ? $post['site'] : null;
                if (!$site || !$database) {
                    return Json::fail('安装数据失败');
                }
                try {
                    # 写入站点名称
                    $sql = "INSERT INTO `{$database['prefix']}system_config` VALUES (1, '2023-07-06 11:01:59', '2023-07-20 11:32:50', 'system_config', '站点名称', 'web_name', '{$site['web_name']}', 'input', '', '请输入站点名称', 0, NULL, NULL, '20');";
                    # 执行SQL
                    Db::exceSQL($sql);
                    # 写入站点域名
                    $sql = "INSERT INTO `{$database['prefix']}system_config` VALUES (2, '2023-07-06 11:01:59', '2023-07-06 11:01:59', 'system_config', '站点域名', 'web_url', '{$site['web_url']}', 'input', '', '请输入站点域名', 0, NULL, NULL, '20');";
                    # 执行SQL
                    Db::exceSQL($sql);
                    # 获取管理员参数
                    $username = isset($site['username']) ? $site['username'] : '';
                    $password = isset($site['password']) ? $site['password'] : '';
                    # 写入管理员信息
                    $password = Password::passwordHash($password);
                    $sql      = "INSERT INTO `{$database['prefix']}system_admin` VALUES (1,'2023-07-06 11:01:59', '2023-07-06 11:01:59', 1, 0, '{$username}', '{$password}', '1', '系统管理员', '', NULL, NULL, '', '20');";
                    # 执行SQL
                    Db::exceSQL($sql);
                    # 安装完成
                    return Json::json('安装站点数据完成...', 200, [
                        'next' => 'supervisor'
                    ]);
                } catch (\Throwable $e) {
                    return Json::failFul($e->getMessage(), 404);
                }
            # 安装守护进程配置
            case 'supervisor':
                $panelLogic->addSupervisor($server_name);
                # 安装完成
                return Json::json(
                    '安装进程成功，开始进行最后设置...',
                    200,
                    [
                        'next' => 'config'
                    ]
                );
            # 写入文件配置
            case 'config':
                # 安装Env配置文件
                Helpers::installEnv($post);
                # 安装Nginx配置
                $panelLogic->addNginx($server_name, $post);
                # 重启一下守护进程
                $panelLogic->reloadSupervisor($server_name);
                # 安装Env配置成功
                # 安装完成,写入版本信息
                $req = new SystemUpdateRequest;
                $req->detail();
                $req->version = 1;
                $req->version_name = '1.0.0';
                $cloud = new Cloud($req);
                $data = $cloud->send();
                $versionJson = json_encode([
                    'version' => $data->version,
                    'version_name' => $data->version_name
                ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                file_put_contents(ROOT_PATH . '/config/version.json', $versionJson);
                return Json::json(
                    '全部安装完成，即将跳转...',
                    200
                );
            default:
                return Json::fail('安装失败...');
        }
    }

    /**
     * 安装前检测
     * @throws \Exception
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function database()
    {
        # 获取数据
        $post = $_POST;
        # 数据验证
        if (!isset($post['btData']))
            return Json::fail('缺少宝塔面板数据');
        if (!isset($post['serverData']))
            return Json::fail('请设置框架启动端口');
        if (!isset($post['serverData']['server_port']))
            return Json::fail('请设置框架启动端口');
        if (!isset($post['database']))
            return Json::fail('缺少数据库信息');
        if (!isset($post['cloud']))
            return Json::fail('缺少云服务信息');
        if (!isset($post['site']))
            return Json::fail('缺少站点信息');
        $btData     = isset($post['btData']) ? $post['btData'] : null;
        $serverData = isset($post['serverData']) ? $post['serverData'] : null;
        $databased  = isset($post['database']) ? $post['database'] : null;
        $cloud      = isset($post['cloud']) ? $post['cloud'] : null;
        $site       = isset($post['site']) ? $post['site'] : null;
        # 数据库验证
        try {
            # 验证数据库版本
            $queryString = Db::pdo()->query("select VERSION() as mysql_version");
            $version     = $queryString->fetchColumn(0);
            if (!$version) {
                throw new Exception('数据库版本检测失败');
            }
            $min_version = '5.6';
            if (version_compare($version, $min_version) <= 0) {
                throw new Exception("数据库要求最低{$min_version}版本");
            }
            # 检测端口是否被占用
            if (Validated::validatePort((int) $serverData['server_port'])) {
                throw new Exception("{$serverData['server_port']}端口已被占用");
            }
            # 验证宝塔面板
            Validated::validateBt($btData);

            # 验证云服务
            Validated::validateCloud($cloud);
            # 验证站点数据
            Validated::validateSite($site);
            # 返回数据
            return Json::successRes($post);
        } catch (\Throwable $e) {
            return Json::failFul($e->getMessage(), 404);
        }
    }
}