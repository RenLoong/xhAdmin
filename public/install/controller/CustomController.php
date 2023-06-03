<?php
use app\utils\Password;

class CustomController
{
    /**
     * 安装程序
     *
     * @return void
     */
    public function install()
    {
        # 获取安装步骤
        $step  = isset($_GET['step']) ? $_GET['step'] : '';
        $total = isset($_GET['total']) ? intval($_GET['total']) : 0;
        $post  = $_POST;
        // 安装数据库结构
        if ($step === 'structure') {
            $database = isset($post['database']) ? $post['database'] : [];
            if (!$database) {
                return Json::fail('获取安装数据库配置失败');
            }
            // 数据库连接
            try {
                $pdo = Db::connect($database);
                // 获取SQL文件树
                $sqlTrees = Dir::tree(KF_INSTALL_PATH . '/data/sql');
                if ($total >= count($sqlTrees)) {
                    return Json::json('安装数据库结构完成...',200,[
                        'next'  => 'database'
                    ]);
                }
                $sqlItem = isset($sqlTrees[$total]) ? $sqlTrees[$total] : null;
                if (is_null($sqlItem)) {
                    throw new Exception('安装数据库结构失败');
                }
                // 替换SQL
                $sql = Helpers::strReplace($sqlItem['path'], $database['prefix']);
                $SQLstatus = $pdo->query($sql);
                $installName = str_replace(['.sql', 'php_'], '', $sqlItem['filename']);
                if ($SQLstatus === false) {
                    throw new Exception("安装 【{$installName}】 数据表结构失败");
                }
                if (is_object($SQLstatus)) {
                    $SQLstatus->fetchAll(PDO::FETCH_ASSOC);
                    return Json::json("安装 【{$installName}】 数据表成功",200, [
                        'next'  => 'structure',
                        'total' => $total + 1
                    ]);
                }
                throw new Exception("安装 【{$installName}】 数据表结构失败");
            } catch (\Throwable $e) {
                return Json::fail($e->getMessage(),404);
            }
        }
        // 写入数据
        if ($step === 'database') {
            $database = isset($post['database']) ? $post['database'] : null;
            $site     = isset($post['site']) ? $post['site'] : null;
            if (is_null($site) || is_null($database)) {
                return Json::fail('安装数据失败');
            }
            $date = date('Y-m-d H:i:s');
            // 获取管理员参数
            $username = isset($site['username']) ? $site['username'] : '';
            $password = isset($site['password']) ? $site['password'] : '';

            try {
                // 数据库连接
                $pdo = Db::connect($database);
                // 写入站点信息
                $configSql = '';
                $configSql .= "INSERT INTO `{$database['prefix']}system_config` VALUES (1,'{$date}', '{$date}', 1, '站点名称', 'web_name', '{$site['web_name']}', 'input', '', '请输入站点名称', 0);";
                $configSql .= "INSERT INTO `{$database['prefix']}system_config` VALUES (2,'{$date}', '{$date}', 1, '站点域名', 'web_url', '{$site['web_url']}', 'input', '', '请输入站点域名', 0);";
                $pdo->query("{$configSql}");

                // 写入管理员信息
                $password = Password::passwordHash($password);
                $adminSql = "INSERT INTO `{$database['prefix']}system_admin` VALUES (1,'{$date}', '{$date}', 1, 0, '{$username}', '{$password}', '1', '系统管理员', '', '{$date}', NULL, '', '0');";
                $pdo->query("{$adminSql}");
                // 安装完成
                return Json::json('安装站点数据完成...',200, [
                    'next' => 'config'
                ]);
            } catch (\Throwable $e) {
                return Json::fail($e->getMessage(), 404);
            }
        }
        // 设置配置文件
        if ($step === 'config') {
            try {
                // 设置Env配置文件
                Helpers::installEnv($post);
                // 成功
                return Json::success('安装配置文件完成，准备跳转中...');
            } catch (\Throwable $e) {
                return Json::fail($e->getMessage(), 404);
            }
        }
        return Json::fail('安装失败...');
    }

    /**
     * 安装检测
     *
     * @return void
     */
    public function database()
    {
        # 获取数据
        $post = $_POST;
        # 数据验证
        if (!isset($post['btData'])) return Json::fail('缺少宝塔面板数据');
        if (!isset($post['serverData'])) return Json::fail('请设置框架启动端口');
        $serverData = isset($post['serverData']) ? $post['serverData'] : null;
        if (!$serverData || !$serverData['server_port']) return Json::fail('请设置框架启动端口');
        if (!isset($post['database'])) return Json::fail('缺少数据库信息');
        if (!isset($post['cloud'])) return Json::fail('缺少云服务信息');
        if (!isset($post['site'])) return Json::fail('缺少站点信息');
        $databased = isset($post['database']) ? $post['database'] : null;
        $cloud     = isset($post['cloud']) ? $post['cloud'] : null;
        $site      = isset($post['site']) ? $post['site'] : null;
        # 数据库验证
        try {
            # 检测端口是否被占用
            if (Validated::validatePort((int)$serverData['server_port'])) {
                throw new Exception("{$serverData['server_port']}端口已被占用");
            }
            # 验证数据连接
            $pdo = Db::connect($databased);
            $mysqlPodSql = $pdo->query("select VERSION() as mysql_version");
            $version = $mysqlPodSql->fetchColumn(0);
            if (!$version) {
                throw new Exception('数据库版本检测失败');
            }
            $min_version = '5.7';
            if (version_compare($version, $min_version) <= 0) {
                throw new Exception("数据库要求最低{$min_version}版本");
            }
            # 验证云服务
            Validated::validateCloud($cloud);
            # 验证站点数据
            Validated::validateSite($site);

            # 返回数据
            return Json::successRes($post);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage(), 404);
        }
    }
}