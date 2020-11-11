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
        'referalStatsCounter' => [
            'class' => tifia\services\ReferalStatsCounter::class
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=142.91.155.196;port=3306;dbname=test6',
            'username' => 'tester',
            'password' => 'Jwsq5YwG0aFcJtaK2xdo',
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
    ]
];
