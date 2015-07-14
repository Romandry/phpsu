<?php
$filePath = __DIR__ . '/../../application/config/main.json';
$patterns = array('~/\*.+?\*/~s', '~\s+?//.+\r?\n~');
$fileData = preg_replace($patterns, '', file_get_contents($filePath));
$config = json_decode($fileData, true);

return array(
    'driver'    => 'pdo_mysql',
    'host'      => $config['db']['host'],
    'user'      => $config['db']['user'],
    'password'  => $config['db']['pass'],
    'dbname'    => $config['db']['dbname']
);