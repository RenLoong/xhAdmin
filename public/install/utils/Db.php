<?php
/**
 * @title 数据库管理服务
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Db
{
    /**
     * 连接数据库
     * @param array $data
     * @return PDO
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function connect(array $data): \PDO
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