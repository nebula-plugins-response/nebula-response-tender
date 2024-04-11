<?php
require '../vendor/autoload.php';
$config = [
    'http'     => [
        'timeout'  => 100,
        'base_uri' => 'https://api.xx.com'
    ],
    'cache'    => [
        // 缓存保存目录
        'path' => __DIR__ . '/cache',
    ],
    'log'      => [
        'level' => 'debug',
        'file'  => __DIR__ . '/log/mafengs.log',
    ],
    'account'  => "xxx",
    'password' => 'xxx',
    'secret'   => "xxx",
    'expire'   => 100
];
$app    = \Nebula\NebulaResponseTender\Factory::Tender($config);


$searchRes = $app->search->send([
    'pageSize'     => 1,
    'purchaseType' => 'INVITE',
    'pageNumber'   => 1
]);
$detaulRes = $app->detail->send(['hashId' => '2024-04-11.25047fa6522f91c0696a2f2121d45137']);
var_dump($detaulRes);
