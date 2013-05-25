<?php
chdir(__DIR__);

$dir  = getcwd();
$prev = '.';
while (!is_dir($dir . '/vendor')) {
    $dir = dirname($dir);
    if ($prev === $dir) {
        $dir = false;
        break;
    }
    $prev = $dir;
}

if ($dir) {
    require $dir . '/vendor/autoload.php';
}
