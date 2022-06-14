<?php


namespace app\models\forms;

use app\models\User;
use yii\base\Model;

class SignupForm extends Model
{
    public $login;
    public $password;
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'password_repeat'], 'safe'],
            [['login', 'password', 'password_repeat'], 'required'],
            ['login', 'string', 'max' => 30],
            ['password', 'string', 'max' => 64],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
            ['login', 'isLoginUnique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль'
        ];
    }

    public function isLoginUnique($attribute)
    {
        $user = User::find()->where([$attribute => $this->$attribute])->one();
        if ($user) {
            $this->addError($attribute, 'Пользователь с таким логином уже существует');
        }
    }

    public function loadSignupData()
    {
        $user = new User();
        $user->login = $this->login;
        $user->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
        $user->role = User::USER_ROLE;
        return $user;
    }
}