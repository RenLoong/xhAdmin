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
     *
     * @return \PDO
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
        $pdo = new \PDO(
            "mysql:host={$data['host']};dbname={$data['database']}",
            $data['username'],
            $data['password']
        );
        return $pdo;
    }
}
