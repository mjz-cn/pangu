<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    /* 默认路由 */
    'defaultRoute' => 'site',
    /* 默认布局文件 优先级 控制器>配置文件>系统默认 */
    'layout' => 'main',
    'components' => [
        'request' => [
            'class' => 'common\core\Request',
            'csrfParam' => '_csrf',
//            'baseUrl' => Yii::getAlias('@backendUrl'), //等于 Yii::getAlias('@web')
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'backend\models\AdminUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        /* 数据库RBAC权限控制 */
        'authManager' => [
            'class' => 'common\core\rbac\DbManager',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /* 链接管理 */
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => env('BACKEND_PRETTY_URL', true), //开启url规则
            'showScriptName' => false,  //是否显示链接中的index.php
            'rules' => [
                //
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
        'class' => 'backend\behaviors\RbacBehavior',
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
