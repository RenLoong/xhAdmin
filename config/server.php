<?php
return [
    'listen' => 'http://0.0.0.0:'.env('KF_SERVER_PORT','39600'),
    'transport' => 'tcp',
    'context' => [],
    'name' => 'KFAdmin',
    'count' => cpu_count() * 4,
    'user' => '',
    'group' => '',
    'reusePort' => false,
    'event_loop' => '',
    'stop_timeout' => 2,
    'pid_file' => runtime_path() . '/kfadmin.pid',
    'status_file' => runtime_path() . '/kfadmin.status',
    'stdout_file' => runtime_path() . '/logs/stdout.log',
    'log_file' => runtime_path() . '/logs/workerman.log',
    'max_package_size' => 50 * 1024 * 1024
];
