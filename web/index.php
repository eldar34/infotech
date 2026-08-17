<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Инициализация phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad(); 

$yiiDebug = isset($_ENV['YII_DEBUG']) && ($_ENV['YII_DEBUG'] === 'true' || $_ENV['YII_DEBUG'] === '1');
defined('YII_DEBUG') or define('YII_DEBUG', $yiiDebug);

$yiiEnv = $_ENV['YII_ENV'] ?? 'prod';
defined('YII_ENV') or define('YII_ENV', $yiiEnv);

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
