<?php


namespace app\models\forms;


use app\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    public $login;
    public $password;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'safe'],
            [['login', 'password'], 'required'],
            ['login', 'string', 'max' => 30],
            ['password', 'string', 'max' => 64],
            ['password', 'validateUser'],
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
        ];
    }

    /**
     * Метод отвечает за валидацию данных пользователя, пытающегося залогиниться.
     */
    public function validateUser()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !self::validatePassword($user, $this->password)) {
                $this->addError('password', 'Неверный email или пароль!');
            }
        }
    }

    /**
     * Метод, отвечающий за валидацию пароля пользователя.
     * @param User $user
     * @param $password
     * @return bool
     */
    public static function validatePassword(User $user, $password)
    {
        return \Yii::$app->security->validatePassword($password, $user->password_hash);
    }

    /**
     * Метод ищет пользователя в базе данных по логину.
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['login' => $this->login]);
        }

        return $this->_user;
    }
}