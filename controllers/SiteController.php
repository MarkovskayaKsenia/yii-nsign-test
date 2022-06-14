<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


class SiteController extends SecuredController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $loginForm = new LoginForm();
        if (\Yii::$app->request->getIsPost()) {
            $loginForm->load(\Yii::$app->request->post());
            $user = $loginForm->getUser();
            if (!$user) {
                $loginForm->addError('login', 'Нет такого пользователя');
            } else {
                if ($loginForm->validate()) {
                   \Yii::$app->user->login($user);
                    return $this->redirect('/');
                }
            }
        }

        return $this->render('login', [
            'loginForm' => $loginForm,
        ]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignup()
    {
        $loginForm = new LoginForm();
        return $this->render('signup', [
            'loginForm' => $loginForm,
        ]);
    }
}
