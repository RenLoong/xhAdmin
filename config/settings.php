<?php

$data = [];
foreach (glob(config_path().'settings/*.php') as $path) {
    $group = basename($path, '.php');
    $data[$group] = require $path;
}
return $data;
