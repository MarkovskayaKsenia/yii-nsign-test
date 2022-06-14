<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\models\User;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;


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
        $signupForm = new SignupForm();

        if (Yii::$app->request->isPost) {
            $signupForm->load(Yii::$app->request->post());
            $signupForm->validate();
            if ($signupForm->hasErrors()) {
                return $this->render('signup', [
                    'signupForm' => $signupForm,
                ]);
            }

            $user = $signupForm->loadSignupData();

            if ($user->save()) {
                \Yii::$app->user->login($user);
                return $this->redirect('/');
            } else {
                $signupForm->addErrors($user->errors);
            }
        }

        return $this->render('signup', [
            'signupForm' => $signupForm,
        ]);
    }
}
