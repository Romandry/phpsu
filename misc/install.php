<?php
/**
 * Created by PhpStorm.
 * User: Panoptik
 * Date: 21.07.15
 * Time: 22:48
 */

$path = __DIR__;
$applicationPath = realpath($path . '/../application');

$hostsConfig = $applicationPath . '/config/hosts.json';
if(!file_exists($hostsConfig)) {
    echo 'Copying hosts config... ';
    $r = copy($path . '/hosts-EXAMPLE.json', $hostsConfig);
    echo $r ? 'OK' : 'FAIL!';
    echo PHP_EOL;
}

$mainConfig = $applicationPath . '/config/main.json';
if(!file_exists($mainConfig)) {
    echo 'Copying main config... ';
    $r = copy($path . '/main-EXAMPLE.json', $mainConfig);
    echo $r ? 'OK' : 'FAIL!';
    echo PHP_EOL;
}

$logDir = $applicationPath . '/logs';
echo 'Check log dir... ';
chdir($applicationPath);
if(!file_exists($logDir)) {
    if(!is_dir($logDir)) {
        echo 'not exists.' . PHP_EOL;
        echo 'Creating directory ' . $logDir . ' ... ';
        $r = mkdir('logs');
        echo $r ? 'OK' : 'FAIL!';
        echo PHP_EOL;
    }
}

echo 'Set permissions ... ';
$r = chmod('logs', 0777);
echo $r ? 'OK' : 'FAIL!';
echo PHP_EOL;