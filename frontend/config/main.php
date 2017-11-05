<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\NormalUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/app_error.log',
                    'maxFileSize' => 1024 * 1024,
                    'maxLogFiles' => 10
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/app_info.log',
                    'maxFileSize' => 1024 * 1024,
                    'maxLogFiles' => 10
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [],
                    'depends' => [
                        'backend\assets\AppAsset'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ],
            ],

        ],
    ],
    'as rbac' => [
        'class' => \frontend\behaviors\RbacBehavior::className(),
        'allowActions' => [
            'site/login', 'site/logout', 'public*', 'debug/*', 'gii/*', // 不需要权限检测
        ]
    ],
    'as verbs' => [
        'class' => \yii\filters\VerbFilter::className(),
        'actions' => [
            'create' => ['get', 'post'],
            'update' => ['get', 'put', 'post'],
            'delete' => ['post', 'delete'],
        ],
    ],
    'params' => $params,
];
