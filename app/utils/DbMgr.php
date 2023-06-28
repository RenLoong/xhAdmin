<?php

namespace app\utils;
use support\Db;

/**
 * Laravel数据库管理函数
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class DbMgr
{
    public static function querySQL(string $sql)
    {
        $sql = self::sqlReplace($sql);
        foreach ($sql as $item) {
            Db::statement($item);
        }
    }
    /**
     * 执行原生SQL
     * @param string $sql
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function query(string $sql)
    {
        return Db::statement($sql);
    }

    /**
     * 检测表是否存在
     * @param string $table
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function hasTable(string $table)
    {
        return self::schema()->hasTable($table);
    }

    /**
     * 检测字段是否存在
     * @param string $table
     * @param string $field
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function hasField(string $table,string $field)
    {
        return self::schema()->hasColumn($table,$field);
    }
    
    /**
     * 替换SQL文件多余字符串
     * @param string $sql
     * @return array<string>|bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function sqlReplace(string $sql)
    {
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $sql = preg_replace('/--.*/i', '', $sql);
        $sql = str_replace("\n","",$sql);
        $sql = str_replace("\r","",$sql);
        $sql = explode(';', $sql);
        $sql = array_filter($sql);
        return $sql;
    }

    /**
     * 获取数据库管理对象
     * @return \Illuminate\Database\Schema\Builder
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function schema()
    {
        return Db::schema();
    }

    /**
     * 获取连接实例
     * @return \Illuminate\Database\Connection
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function instance()
    {
        return Db::connection();
    }
}
