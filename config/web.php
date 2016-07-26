<?php

use yii\web\Request;

$params = require(__DIR__ . '/params.php');
$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());

$config = [
    'id' => 'basic',
    'name' => YII_ENV_PROD ? 'd-site.ru' : $_SERVER['HTTP_HOST'],
    'basePath' => dirname(__DIR__),
    'sourceLanguage' => 'en-US',
    'language' => 'ru-RU',
    'layout' => '@app/views/layouts/default',
    'bootstrap' => [
        'log',
        'app\modules\admin\Bootstrap',
        'app\modules\user\Bootstrap',
        'app\modules\message\Bootstrap',
        'app\modules\settings\Bootstrap',
        'app\modules\page\Bootstrap',
    ],
    'modules' => [
        'admin' => ['class' => 'app\modules\admin\Module'],
        'page' => ['class' => 'app\modules\page\Module'],
        'user' => ['class' => 'app\modules\user\Module'],
        'message' => ['class' => 'app\modules\message\Module'],
        'settings' => ['class' => 'app\modules\settings\Module'],
        'gridview' => ['class' => '\kartik\grid\Module'],
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['@', '?'],
            'disabledCommands' => ['netmount'],
            'roots' => [
                [
                    'baseUrl' => '@web',
                    'basePath' => '@webroot',
                    'path' => 'uploads/images',
                    'name' => 'Изображения'
                ],
                [
                    'baseUrl' => '@web',
                    'basePath' => '@webroot',
                    'path' => 'uploads/files',
                    'name' => 'Файлы'
                ],
            ]
        ]
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'seo' => [
            'class' => 'app\modules\settings\components\SeoComponent',
        ],
        'request' => [
            'baseUrl' => $baseUrl,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'd MMMM yyyy',
        ],
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => YII_DEBUG ? true : false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'baseUrl' => $baseUrl,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '' => '/site/index',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
