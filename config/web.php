<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
return [
    'name' => 'PHP Terminal',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'components' => [
        'request' => [
            'cookieValidationKey' => 'asd-usjzfAZasd',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => (DIRECTORY_SEPARATOR == '/'), // if not windows :)
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl'       => true,
            'showScriptName'        => false,
            'enableStrictParsing'   => true,
            'rules' => [
                '' => 'site/index',
                '<_c>/<_a>' => '<_c>/<_a>',
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];
