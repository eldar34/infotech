<?php

declare(strict_types=1);

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

require __DIR__ . '/../vendor/autoload.php';

// Инициализация phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad(); 

$yiiDebug = isset($_ENV['YII_DEBUG']) && ($_ENV['YII_DEBUG'] === 'true' || $_ENV['YII_DEBUG'] === '1');
defined('YII_DEBUG') or define('YII_DEBUG', $yiiDebug);

$yiiEnv = $_ENV['YII_ENV'] ?? 'prod';
defined('YII_ENV') or define('YII_ENV', $yiiEnv);

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$c3 = dirname(__DIR__) . '/c3.php';

if (file_exists($c3)) {
    require_once $c3;
}

$config = require __DIR__ . '/../config/test.php';

(new yii\web\Application($config))->run();
