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

                // Seg_Usuario
                'seg-usuario'=>'seg-usuario/index',
                'seg-usuario/view/<id>'=>'seg-usuario/view',
                'seg-usuario/update/<id>'=>'seg-usuario/update',
                'seg-usuario/delete/<id>'=>'seg-usuario/delete',

                // Adm_Persona
                'adm-persona'=>'adm-persona/index',
                'adm-persona/view/<id>'=>'adm-persona/view',
                'adm-persona/create'=>'adm-persona/create',
                'adm-persona/update/<id>'=>'adm-persona/update',
                'adm-persona/delete/<id>'=>'adm-persona/delete',

                // Doc_Especialidad
                'doc-especialidad'=>'doc-especialidad/index',
                'doc-especialidad/view/<id>'=>'doc-especialidad/view',
                'doc-especialidad/create'=>'doc-especialidad/create',
                'doc-especialidad/update/<id>'=>'doc-especialidad/update',
                'doc-especialidad/delete/<id>'=>'doc-especialidad/delete',

                // Doc_Estudiante
                'doc-estudiante'=>'doc-estudiante/index',
                'doc-estudiante/view/<id>'=>'doc-estudiante/view',
                'doc-estudiante/create'=>'doc-estudiante/create',
                'doc-estudiante/update/<id>'=>'doc-estudiante/update',
                'doc-estudiante/delete/<id>'=>'doc-estudiante/delete',

                // Doc_Grupo
                'doc-grupo'=>'doc-grupo/index',
                'doc-grupo/view/<id>'=>'doc-grupo/view',
                'doc-grupo/create'=>'doc-grupo/create',
                'doc-grupo/update/<id>'=>'doc-grupo/update',
                'doc-grupo/delete/<id>'=>'doc-grupo/delete',

                // Doc_Profesor
                'doc-profesor'=>'doc-profesor/index',
                'doc-profesor/view/<id>'=>'doc-profesor/view',
                'doc-profesor/create'=>'doc-profesor/create',
                'doc-profesor/update/<id>'=>'doc-profesor/update',
                'doc-profesor/delete/<id>'=>'doc-profesor/delete',

                'doc-profesor/especialidades/<id>'=>'doc-profesor/especialidades',
                'doc-profesor/unsolicited-especialidades/<id>'=>'doc-profesor/unsolicited-especialidades',
                'doc-profesor/create-especialidad'=>'doc-profesor/create-especialidad',
                'doc-profesor/delete-especialidad/<idprofesor:\d+>/<idespecialidad:\d+>'=>'doc-profesor/delete-especialidad',

                'doc-profesor/grupos/<id>'=>'doc-profesor/grupos',
                'doc-profesor/unsolicited-groups/<id>'=>'doc-profesor/unsolicited-groups',
                'doc-profesor/create-grupo'=>'doc-profesor/create-grupo',
                'doc-profesor/delete-grupo/<idprofesor:\d+>/<idgrupo:\d+>'=>'doc-profesor/delete-grupo',

                // Idioma
                'idioma'=>'idioma/index',
                'idioma/view/<id>'=>'idioma/view',
                'idioma/create'=>'idioma/create',
                'idioma/update/<id>'=>'idioma/update',
                'idioma/delete/<id>'=>'idioma/delete',

                 // Idioma
                 'plantilla'=>'plantilla/index',
                 'plantilla/view/<id>'=>'plantilla/view',
                 'plantilla/create'=>'plantilla/create',
                 'plantilla/update/<id>'=>'plantilla/update',
                 'plantilla/delete/<id>'=>'plantilla/delete',

                // Tipo_Entidad
                'tipo-entidad'=>'tipo-entidad/index',
                'tipo-entidad/view/<id>'=>'tipo-entidad/view',
                'tipo-entidad/create'=>'tipo-entidad/create',
                'tipo-entidad/update/<id>'=>'tipo-entidad/update',
                'tipo-entidad/delete/<id>'=>'tipo-entidad/delete',

                // Entidad
                'entidad'=>'entidad/index',
                'entidad/view/<id>'=>'entidad/view',
                'entidad/create'=>'entidad/create',
                'entidad/update/<id>'=>'entidad/update',
                'entidad/delete/<id>'=>'entidad/delete',

                // Tipo_Asociacion
                'tipo-asociacion'=>'tipo-asociacion/index',
                'tipo-asociacion/view/<id>'=>'tipo-asociacion/view',
                'tipo-asociacion/create'=>'tipo-asociacion/create',
                'tipo-asociacion/update/<id>'=>'tipo-asociacion/update',
                'tipo-asociacion/delete/<id>'=>'tipo-asociacion/delete',

                // Asociacion
                'asociacion'=>'asociacion/index',
                'asociacion/view/<id>'=>'asociacion/view',
                'asociacion/create'=>'asociacion/create',
                'asociacion/update/<id>'=>'asociacion/update',
                'asociacion/delete/<id>'=>'asociacion/delete',

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
                '<controller:\w+>/<action:\w+>/<idprofesor:\d+>/<idgrupo:\d+>' => '<controller>/<action>',
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
