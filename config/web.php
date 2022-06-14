<?php



$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-Ru',
    'timeZone' => 'Europe/Moscow',
    'name' => 'Крошка-картошка',
    'defaultRoute' => 'recipes/index',
    'homeUrl' => '/',
    'on beforeAction' => function ($event) {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->getIdentity();
            $user->last_visit_date = null;
            $user->save();
        }
    },
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'UU8UvR-xyigPve22-hofIqGmA3uPfWlI',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/' => 'recipes/index',
                'ingredients' => 'ingredients/index',
                'ingredient/create/' => 'ingredients/create',
                'ingredient/show/<ingredientId:\d+>/' => 'ingredients/show',
                'ingredient/edit/<ingredientId:\d+>/' => 'ingredients/edit',
                'ingredient/delete/<ingredientId:\d+>/' => 'ingredients/delete',
                'recipe/create/' => 'recipes/create',
                'recipe/show/<recipeId:\d+>' => 'recipes/show',
                'recipe/edit/<recipeId:\d+>' => 'recipes/edit',
                'recipe/delete/<recipeId:\d+>' => 'recipes/delete',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'signup' => 'site/signup'
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
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

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
