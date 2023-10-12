<?php

$data = [];
foreach (glob(__DIR__.'settings/*.php') as $path) {
    $group = basename($path, '.php');
    $data[$group] = require $path;
}
return $data;
