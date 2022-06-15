<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "recipe".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $user_id
 *
 * @property RecipeIngredient[] $recipeIngredients
 * @property User $user
 */
class Recipe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recipe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'user_id'], 'required'],
            [['description'], 'string', 'min' => 20],
            [['user_id'], 'integer'],
            [['title'], 'string', 'min' => 5,'max' => 150],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'description' => 'Описание',

        ];
    }

    public function getRecipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class, ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getRecipeIngredientsList()
    {
        return $this->hasMany(Ingredient::class, ['id' => 'ingredient_id'])
            ->viaTable('recipe_ingredient', ['recipe_id' => 'id'])->inverseOf('recipeIngredients');
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
