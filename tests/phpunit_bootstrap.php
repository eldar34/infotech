<?php

// phpcs:ignoreFile

declare(strict_types=1);

// Переводим приложение в режим тестирования
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

// Подключаем автозагрузчик Composer и сам Yii
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Загружаем конфигурацию тестового приложения
$config = require __DIR__ . '/../config/test.php';

// Инициализируем приложение, чтобы работал класс \Yii::$app
new yii\web\Application($config);
