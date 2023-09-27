<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

/*use \yii\web\Request;
$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());*/

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
             // permits any and all IPs
             // you should probably restrict this
            'allowedIPs' => ['*']
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            //'baseUrl'=>'/quotation-system/backend'
            //'baseUrl'=>'/backend'
        ],
        /*'assetManager' => [
            'basePath'=>'/web/assets'
        ],*/
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
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
        'urlManager' => [
            //'scriptUrl'=>'/backend/index.php',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            /*'rules' => [
                '<module:\w+>/<controller:\w+>/<action:[\w-]+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>'                 => '<module>/<controller>',
                '<module:\w+>'                                  => '<module>',    
            ]*/
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest']
        ],
        'formatter' => [
            'class'=> \backend\i18n\Formatter::class,
            'datetimeFormat' => 'php:d/m/Y H:i'
        ]
    ],
    'container' => [
        'definitions' => [
             \yii\widgets\LinkPager::class => \yii\bootstrap4\LinkPager::class,
             'yii\bootstrap4\LinkPager' => [
                 'firstPageLabel' => 'First',
                 'lastPageLabel'  => 'Last'
           ]
        ],
     ],
    'params' => $params,
];
