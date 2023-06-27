<?php
use Illuminate\Database\Capsule\Manager as Capsule;


/**
 * @title 数据库管理服务
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Db
{
    /**
     * 根据表执行
     * @param string $table
     * @return Illuminate\Database\Query\Builder
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function table(string $table)
    {
        return Capsule::table($table);
    }

    /**
     * 执行SQL语句
     * @param string $sql
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function query(string $sql)
    {
        return Capsule::statement($sql);
    }


    /**
     * 连接数据库
     * @param array $config
     * @throws \PDOException
     * @return Illuminate\Database\DatabaseManager
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function connect(array $config)
    {
        if (empty($config['host'])) {
            throw new PDOException('请输入主机地址');
        }
        if (empty($config['database'])) {
            throw new PDOException('请输入数据库名称');
        }
        if (empty($config['username'])) {
            throw new PDOException('请输入数据库用户');
        }
        if (empty($config['password'])) {
            throw new PDOException('请输入数据库密码');
        }
        if (empty($config['port'])) {
            throw new PDOException('请输入数据库端口');
        }
        $_config = [
            'driver' => 'mysql',
            'host' => $config['host'],
            'port' => $config['port'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password'],
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'options' => [
                \PDO::ATTR_TIMEOUT => 3
            ]
        ];
        $capsule = new Capsule;
        //创建一个数据库的链接
        $capsule->addConnection($_config);
        //静态可访问
        $capsule->setAsGlobal();
        //启动Eloquent，实际上就是解析链接信息，开始建立数据库的链接
        $capsule->bootEloquent();
    }

    /**
     * Summary of sqlReplace
     * @param string $sql
     * @return array|string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function sqlReplace(string $sql)
    {
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $sql = str_replace("\n\n","",$sql);
        return $sql;
    }

    /**
     * 连接数据库
     * @param array $data
     * @return PDO
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function connect1(array $data): \PDO
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
        $dsn    = "mysql:host={$data['host']};dbname={$data['database']};port={$data['port']};";
        $params = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8mb4",
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_TIMEOUT => 5,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];
        return new \PDO($dsn, $data['username'], $data['password'], $params);
    }
}