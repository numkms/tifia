<?php
return [
    'id' => 'tifia-app',
    'bootstrap' => ['gii'],
    'basePath' => __DIR__ . "/../",
    'controllerNamespace' => 'tifia\controllers',
    'aliases' => [
        '@tifia' => __DIR__ . "/../",
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ]
    ],
    'components' => [
        'fileCache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@tifia/console/runtime/cache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=142.91.155.196;port=3306;dbname=test6',
            'username' => 'tester',
            'password' => 'Jwsq5YwG0aFcJtaK2xdo',
            'enableSchemaCache' => true,
            'schemaCache' => 'fileCache'
        ]
    ]
];
