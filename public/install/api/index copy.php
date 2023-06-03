<?php

use app\admin\service\kfcloud\CloudService;
use app\admin\service\kfcloud\HttpService;
use app\utils\Password;
use Webman\Config;

define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));
define('KF_INSTALL_PATH', dirname(__DIR__));
class Install
{
    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-19
     */
    public function __construct()
    {
        // 检测是否安装
        if (file_exists(ROOT_PATH . '/.env')) {
            exit($this->json('success', 200, [
                'install'   => 'ok',
                'desc'      => '恭喜您，安装成功',
            ]));
        }
        // 加载配置项
        Config::load(ROOT_PATH . '/config');
    }

    /**
     * 欢迎使用安装程序
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    public function index()
    {
        return $this->json('欢迎使用安装程序', 200);
    }

    /**
     * 安装协议
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public function step1()
    {
        $content = file_get_contents(KF_INSTALL_PATH . '/agreement.txt');
        return $this->json('success', 200, [
            'text' => $content
        ]);
    }

    /**
     * 环境检测
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    public function step2()
    {
        $data = [
            'fun'   => $this->getVerifyFun(),
            'extra' => $this->getVerifyExtra()
        ];
        return $this->json('success', 200, $data);
    }

    /**
     * 安装前检测
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    public function step3()
    {
        // 数据验证
        if (!isset($_POST['database']))
            return $this->json('缺少数据库信息');
        if (!isset($_POST['cloud']))
            return $this->json('缺少云服务信息');
        if (!isset($_POST['site']))
            return $this->json('缺少站点信息');
        $databased = isset($_POST['database']) ? $_POST['database'] : null;
        $cloud     = isset($_POST['cloud']) ? $_POST['cloud'] : null;
        $site      = isset($_POST['site']) ? $_POST['site'] : null;
        // 数据库验证
        try {
            $pdo = $this->connectDb($databased);
            $mysqlPodSql = $pdo->query("select VERSION() as mysql_version");
            $version = $mysqlPodSql->fetchColumn(0);
            if (!$version) {
                throw new Exception('数据库版本检测失败');
            }
            $min_version = '5.7';
            if (version_compare($version, $min_version) <= 0) {
                throw new Exception("数据库要求最低{$min_version}版本");
            }
        } catch (\Throwable $e) {
            return $this->json($e->getMessage(), 404);
        }
        // 其他验证
        try {
            $this->validateCloud($cloud);
            $this->validateSite($site);
        } catch (\Throwable $e) {
            return $this->json($e->getMessage());
        }
        return $this->json('验证通过', 200, $_POST);
    }

    /**
     * 发送POST请求
     * @param string $username
     * @param string $password
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    private function curlPost(string $username, string $password)
    {
        $data    = [
            'username' => $username,
            'password' => $password,
            'scode'    => 'no'
        ];
        $referer = $_SERVER['HTTP_REFERER'];
        $url     = HttpService::$url . 'User/login';
        $headers = [
            'X-Requested-With:XMLHttpRequest',
        ];
        $ch      = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result ? json_decode($result, true) : null;
    }

    /**
     * 验证站点输入
     * @param array $data
     * @throws Exception
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    private function validateSite(array $data)
    {
        if (!isset($data['web_name']) || empty($data['web_name'])) {
            throw new Exception('请输入站点名称');
        }
        if (!isset($data['web_url']) || empty($data['web_url'])) {
            throw new Exception('请输入站点域名');
        }
        if (!filter_var($data['web_url'], FILTER_VALIDATE_URL)) {
            throw new Exception('请输入正确的域名地址');
        }
        if (!isset($data['username']) || empty($data['username'])) {
            throw new Exception('请输入站点管理员账号');
        }
        if (!isset($data['password']) || empty($data['password'])) {
            throw new Exception('请输入站点管理员密码');
        }
        return $data;
    }

    /**
     * 验证云服务
     * @param array $data
     * @throws Exception
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    private function validateCloud(array $data)
    {
        if (!isset($data['username']) || empty($data['username'])) {
            throw new Exception('请输入云服务账号');
        }
        if (!isset($data['password']) || empty($data['password'])) {
            throw new Exception('请输入云服务账号');
        }
        $response = $this->curlPost($data['username'], $data['password']);
        if (!$response) {
            throw new Exception('云服务请求失败');
        }
        if (!isset($response['code'])) {
            throw new Exception('云服务接口错误');
        }
        if ($response['code'] !== 200) {
            throw new Exception($response['msg'], $response['code']);
        }
        // 缓存Redis
        $redis = self::connectRedis();
        $loginStatus = $redis->set(CloudService::$loginToken, $response['data']['token']);
        if (!$loginStatus) {
            throw new Exception('登录云服务失败');
        }
        return $data;
    }

    // 链接redis
    private static function connectRedis()
    {
        $config = require ROOT_PATH . "/config/redis.php";
        $config = isset($config['default']) ? $config['default'] : $config;
        $redis  = new \Redis;
        $redis->connect($config['host'], $config['port']);
        return $redis;
    }

    /**
     * 链接数据库
     * @param array $data
     * @throws PDOException
     * @return PDO
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    private function connectDb(array $data)
    {
        if (!isset($data['host']) || empty($data['host'])) {
            throw new PDOException('请输入主机地址');
        }
        if (!isset($data['database']) || empty($data['database'])) {
            throw new PDOException('请输入数据库名称');
        }
        if (!isset($data['username']) || empty($data['username'])) {
            throw new PDOException('请输入数据库用户');
        }
        if (!isset($data['password']) || empty($data['password'])) {
            throw new PDOException('请输入数据库密码');
        }
        if (!isset($data['port']) || empty($data['port'])) {
            throw new PDOException('请输入数据库端口');
        }
        $pdo = new PDO(
            "mysql:host={$data['host']};dbname={$data['database']}",
            $data['username'],
            $data['password']
        );
        return $pdo;
    }

    /**
     * 安装进行
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    public function step4()
    {
        $step  = isset($_GET['step']) ? $_GET['step'] : '';
        $total = isset($_GET['total']) ? intval($_GET['total']) : 0;
        $post  = $_POST;
        // 安装数据库结构
        if ($step === 'structure') {
            $database = isset($post['database']) ? $post['database'] : [];
            if (!$database) {
                return $this->json('获取安装数据库配置失败');
            }
            // 数据库连接
            try {
                $pdo = $this->connectDb($database);
                // 获取SQL文件树
                $sqlTrees = Dir::tree(__DIR__ . '/sql');
                if ($total >= count($sqlTrees)) {
                    return $this->json('安装数据库结构完成...', 200, [
                        'next' => 'database'
                    ]);
                }
                $sqlItem = isset($sqlTrees[$total]) ? $sqlTrees[$total] : null;
                if (is_null($sqlItem)) {
                    throw new PDOException('安装数据库结构失败');
                }
                // 替换SQL
                $sql = $this->strReplace($sqlItem['path'], $database['prefix']);
                $SQLstatus = $pdo->query($sql);
                $installName = str_replace(['.sql', 'php_'], '', $sqlItem['filename']);
                if ($SQLstatus === false) {
                    throw new PDOException("安装 【{$installName}】 数据表结构失败");
                }
                if (is_object($SQLstatus)) {
                    $SQLstatus->fetchAll(PDO::FETCH_ASSOC);
                    return $this->json("安装 【{$installName}】 数据表成功", 200, [
                        'next'  => 'structure',
                        'total' => $total + 1
                    ]);
                }
                throw new PDOException("安装 【{$installName}】 数据表结构失败");
            } catch (PDOException $e) {
                return $this->json($e->getMessage(), 404);
            }
        }
        // 写入数据
        if ($step === 'database') {
            $database = isset($post['database']) ? $post['database'] : null;
            $site     = isset($post['site']) ? $post['site'] : null;
            if (is_null($site) || is_null($database)) {
                return $this->json('安装数据失败', 404);
            }
            $date = date('Y-m-d H:i:s');
            // 获取管理员参数
            $username = isset($site['username']) ? $site['username'] : '';
            $password = isset($site['password']) ? $site['password'] : '';

            try {
                // 数据库连接
                $pdo = $this->connectDb($database);
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
                return $this->json('安装站点数据完成...', 200, [
                    'next' => 'config'
                ]);
            } catch (PDOException $e) {
                return $this->json($e->getMessage(), 404);
            }
        }
        // 设置配置文件
        if ($step === 'config') {
            try {
                // 设置Env配置文件
                $this->installEnv($post);
                // 重启框架
                posix_kill(posix_getppid(), SIGINT);
                // 成功
                return $this->json('安装配置文件完成，准备跳转中...', 200);
            } catch (\Throwable $e) {
                return $this->json($e->getMessage(), 404);
            }
        }
        return $this->json('安装失败');
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
    private function installEnv(array $post)
    {
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
        $envTplPath = __DIR__ . "/env.tpl";
        $envPath    = ROOT_PATH . "/.env";

        // 读取配置文件
        $envConfig = file_get_contents($envTplPath);

        // 云服务
        $response = CloudService::installSite(
            $site['web_name'],
            $site['web_url']
        )->array();
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
            "{UPLOAD_PUBLIC_URL}"
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
            $web_url
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
    private function strReplace(string $sqlPath, string $prefix): string
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

    /**
     * 安装完成
     * @param 
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    public function step5()
    {
        return $this->json('', 200);
    }

    /**
     * 获取需要验证的开启函数
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    private function getVerifyFun()
    {
        $funPath = KF_INSTALL_PATH . '/config/func.php';
        if (!file_exists($funPath)) {
            throw new Exception('禁用函数配置文件错误');
        }
        $data = require_once $funPath;
        foreach ($data as $key => $value) {
            $data[$key]['status'] = function_exists($value['name']) ? true : false;
            $data[$key]['value']  = function_exists($value['name']) ? 'OK' : 'Fail';
        }
        return $data;
    }


    /**
     * 验证需要开启的扩展
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    private function getVerifyExtra()
    {
        $funPath = KF_INSTALL_PATH . '/config/extra.php';
        if (!file_exists($funPath)) {
            throw new Exception('扩展配置文件错误');
        }
        $data = require_once $funPath;
        foreach ($data as $key => $value) {
            switch ($value['type']) {
                case 'extra':
                    $data[$key]['status'] = extension_loaded($value['name']) ? true : false;
                    $data[$key]['value']  = extension_loaded($value['name']) ? 'OK' : 'Fail';
                    break;
                case 'class':
                    $data[$key]['status'] = class_exists($value['name']) ? true : false;
                    $data[$key]['value']  = class_exists($value['name']) ? 'OK' : 'Fail';
                    break;
                case 'function':
                    $data[$key]['status'] = function_exists($value['name']) ? true : false;
                    $data[$key]['value']  = function_exists($value['name']) ? 'OK' : 'Fail';
                    break;
                case 'version':
                    if ($value['name'] === 'php') {
                        $max = (bool)version_compare(PHP_VERSION, $value['min'], '>=');
                        $min = (bool)version_compare(PHP_VERSION, $value['max'], '<');
                        $data[$key]['status'] = $max && $min;
                        $data[$key]['value']  = $data[$key]['status'] ? 'OK' : "{{$value['name']}}必须是 >={$value['version']}以及<{$value['max']}";
                    }
                    break;
            }
        }
        return $data;
    }



    /**
     * 返回JSON数据
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    private function json(string $msg, int $code = 404, array $data = [])
    {
        $json['msg']  = $msg;
        $json['code'] = $code;
        $json['data'] = $data;
        return json_encode($json);
    }
}
header('content-type:application/json');
require ROOT_PATH . "/vendor/autoload.php";
require KF_INSTALL_PATH . "/utils/Dir.php";
try {
    $class  = new Install;
    $action = isset($_GET['act']) ? $_GET['act'] : 'index';
    exit($class->$action());
} catch (\Throwable $e) {
    exit(json_encode([
        'msg'  => $e->getMessage(),
        'code' => $e->getCode(),
        'data' => []
    ]));
}
