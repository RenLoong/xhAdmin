<?php

namespace app\admin\utils;

use Exception;
use Illuminate\Database\Schema\Builder;
use support\Db;
use Support\Exception\BusinessException;
use Throwable;

class Util
{
    /**
     * 获取连接数据库
     */
    static function db()
    {
        return Db::connection('plugin.hangpu8.admin.mysql');
    }

    /**
     * 获取Laravel数据库连接实例
     *
     * @return Builder
     */
    static function schema(): Builder
    {
        return Db::schema('plugin.hangpu8.admin.mysql');
    }

    /**
     * 获取数据库配置
     *
     * @return void
     */
    static function getConfig()
    {
        return config('plugin.hangpu8.admin.database');
    }

    /**
     * 获取当前使用数据库
     *
     * @return array
     */
    static function getDatabase(): array
    {
        $config = self::getConfig();
        $default = isset($config['default']) ? $config['default'] : 'mysql';
        $connections = isset($config['connections']) ? $config['connections'] : [];
        return isset($connections[$default]) ? $connections[$default] : [];
    }

    /**
     * 获取密码散列值
     *
     * @param string $password
     * @param [type] $algo
     * @return string
     */
    static public function passwordHash(string $password, string $algo = PASSWORD_DEFAULT): string
    {
        return password_hash($password, $algo);
    }

    /**
     * 验证密码
     *
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    static public function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * 检测表名
     *
     * @param string $table
     * @return boolean
     */
    static public function checkTableName(string $table): bool
    {
        if (is_numeric($table)) {
            throw new Exception('表名不能是纯数字');
        }
        if (!preg_match('/^[a-zA-Z_0-9]+$/', $table)) {
            throw new Exception('表名不合法');
        }
        return true;
    }

    public static function camel($value)
    {
        static $cache = [];
        $key = $value;

        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return $cache[$key] = str_replace(' ', '', $value);
    }

    public static function smCamel($value)
    {
        return lcfirst(static::camel($value));
    }

    public static function getCommentFirstLine($comment)
    {
        if ($comment === false) {
            return false;
        }
        foreach (explode("\n", $comment) as $str) {
            if ($s = trim($str, "*/\ \t\n\r\0\x0B")) {
                return $s;
            }
        }
        return $comment;
    }

    public static function methodControlMap()
    {
        return  [
            //method=>[控件]
            'integer' => ['InputNumber'],
            'string' => ['Input'],
            'text' => ['InputTextArea'],
            'date' => ['DatePicker'],
            'enum' => ['Select'],
            'float' => ['Input'],

            'tinyInteger' => ['InputNumber'],
            'smallInteger' => ['InputNumber'],
            'mediumInteger' => ['InputNumber'],
            'bigInteger' => ['InputNumber'],

            'unsignedInteger' => ['InputNumber'],
            'unsignedTinyInteger' => ['InputNumber'],
            'unsignedSmallInteger' => ['InputNumber'],
            'unsignedMediumInteger' => ['InputNumber'],
            'unsignedBigInteger' => ['InputNumber'],

            'decimal' => ['Input'],
            'double' => ['Input'],

            'mediumText' => ['InputTextArea'],
            'longText' => ['InputTextArea'],

            'dateTime' => ['DatePicker'],

            'time' => ['DatePicker'],
            'timestamp' => ['DatePicker'],

            'char' => ['Input'],

            'binary' => ['Input'],
        ];
    }

    public static function typeToControl($type)
    {
        if (stripos($type, 'int') !== false) {
            return 'InputNumber';
        }
        if (stripos($type, 'time') !== false || stripos($type, 'date') !== false) {
            return 'DatePicker';
        }
        if (stripos($type, 'text') !== false) {
            return 'InputTextArea';
        }
        if ($type === 'enum') {
            return 'Select';
        }
        return 'Input';
    }

    public static function typeToMethod($type, $unsigned = false)
    {
        if (stripos($type, 'int') !== false) {
            $type = str_replace('int', 'Integer', $type);
            return $unsigned ? "unsigned" . ucfirst($type) : lcfirst($type);
        }
        $map = [
            'int' => 'integer',
            'varchar' => 'string',
            'mediumtext' => 'mediumText',
            'longtext' => 'longText',
            'datetime' => 'dateTime',
        ];
        return $map[$type] ?? $type;
    }

    /**
     * reload webman
     *
     * @return bool
     */
    public static function reloadWebman()
    {
        if (function_exists('posix_kill')) {
            try {
                posix_kill(posix_getppid(), SIGUSR1);
                return true;
            } catch (Throwable $e) {
            }
        }
        return false;
    }
}
