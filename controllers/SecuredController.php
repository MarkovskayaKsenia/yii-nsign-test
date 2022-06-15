<?php


namespace app\controllers;


use yii\web\Controller;

class SecuredController extends Controller
{
    /**
     * Метод, определяющий уровень доступа для залогиненных и незалогиненных пользователей, для простых пользователей и админов.
     * @return array|array[]
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['site', 'recipes'],
                        'actions' => ['login', 'error', 'index', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['site', 'recipes'],
                        'actions' => ['logout', 'error', 'index', 'show'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['recipes', 'ingredients'],
                        'actions' => ['index', 'create', 'edit', 'delete', 'show'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->isAdmin();
                        },
                    ],
                ],
            ]
        ];
    }
}