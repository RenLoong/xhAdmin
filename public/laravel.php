<?php
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . "/../vendor/autoload.php";

$capsule = new Capsule;

$_config = [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'test88888',
    'username' => 'test88888',
    'password' => 'test88888',
    'unix_socket' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix' => 'yc_',
    'strict' => true,
    'engine' => null,
    'options' => [
        \PDO::ATTR_TIMEOUT => 3
    ]
];
//创建一个数据库的链接
$capsule->addConnection($_config);
//静态可访问
$capsule->setAsGlobal();
//启动Eloquent，实际上就是解析链接信息，开始建立数据库的链接
$capsule->bootEloquent();


$data = DB::table('system_admin')->first()->toArray();
print_r($data);