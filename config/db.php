<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => ($_ENV['DB_DRIVER'] ?? 'mysql') . ':host=' . ($_ENV['DB_HOST'] ?? 'db') . 
             ';port=' . ($_ENV['DB_PORT'] ?? '3306') . 
             ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'tech_localc_db'), 
    'username' => $_ENV['DB_USERNAME'] ?? 'demo_tech_user',
    'password' => $_ENV['DB_PASSWORD'] ?? 'demo_tech.pass',
    'charset' => 'utf8mb4', 

    // Опции кэширования схемы для продакшена
    // 'enableSchemaCache' => YII_ENV_PROD,
    // 'schemaCacheDuration' => 60,
    // 'schemaCache' => 'cache',
];
