<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $password_hash
 * @property string $role
 * @property string $reg_date
 * @property string $last_visit_date
 *
 * @property Recipe[] $recipes
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const USER_ROLE = 'user';
    const ADMIN_ROLE = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password_hash'], 'required'],
            [['role'], 'string'],
            [['login'], 'string', 'max' => 30],
            [['password_hash'], 'string', 'max' => 64],
            [['login'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password_hash' => 'Password Hash',
            'role' => 'Роль',
            'reg_date' => 'Reg Date',
            'last_visit_date' => 'Last Visit Date',
        ];
    }

    /**
     * Gets query for [[Recipes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipes()
    {
        return $this->hasMany(Recipe::class, ['user_id' => 'id']);
    }

    /**
     * Метод, проверяющий является ли пользователь админом
     * @return bool
     */
    public function isAdmin()
    {
        return !!User::findOne(['id' => Yii::$app->user->getId(), 'role' => self::ADMIN_ROLE]);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
