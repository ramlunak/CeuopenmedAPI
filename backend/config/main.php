<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'language' => 'es-ES',
    'components' => [
        /* 'request' => [
             'csrfParam' => '_csrf-backend',
         ], */
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        /*'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            // ...
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
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'register'=>'site/register',
                'authorize'=>'site/authorize',
                'accesstoken'=>'site/accesstoken',
                'me'=>'site/me',
                'logout'=>'site/logout',

                // Seg_Rol
                'seg-rol'=>'seg-rol/index',
                'seg-rol/view/<id>'=>'seg-rol/view',
                'seg-rol/create'=>'seg-rol/create',
                'seg-rol/update/<id>'=>'seg-rol/update',
                'seg-rol/delete/<id>'=>'seg-rol/delete',

                // Adm_Persona
                'adm-persona'=>'adm-persona/index',
                'adm-persona/view/<id>'=>'adm-persona/view',
                'adm-persona/create'=>'adm-persona/create',
                'adm-persona/update/<id>'=>'adm-persona/update',
                'adm-persona/delete/<id>'=>'adm-persona/delete',

                // Begin borrar ejemplo
                'employees'=>'employee/index',
                'employees/view/<id>'=>'employee/view',
                'employees/create'=>'employee/create',
                'employees/update/<id>'=>'employee/update',
                'employees/delete/<id>'=>'employee/delete',
                // End borrar ejemplo

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                // '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],

        ],


        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'employee'],
            ],
        ],
        */


        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */

    ],
    'params' => $params,
];
