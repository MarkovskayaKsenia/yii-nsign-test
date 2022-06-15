<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ingredient".
 *
 * @property int $id
 * @property string $name
 * @property int $hidden
 *
 * @property RecipeIngredient[] $recipeIngredients
 */
class Ingredient extends \yii\db\ActiveRecord
{
    const IS_HIDDEN = 1;
    const NOT_HIDDEN = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'hidden'], 'safe'],
            [['name'], 'required'],
            [['hidden'], 'integer', 'min' => 0, 'max' => 1],
            ['name', 'string', 'max' => 25, 'min' => 2,
                'tooShort' => "Не меньше {min} символов", 'tooLong' => "Не больше {max} символов"],
            [['name'], 'unique', 'message' => 'Такой ингредиент уже существует'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'hidden' => 'Скрыт',
        ];
    }

    /**
     * Gets query for [[RecipeIngredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class, ['ingredient_id' => 'id']);
    }

    /**
     * Метод возвращает двумерныц массив из не скрытых ингредиентов.
     * @return array
     */
    public static function getNotHiddenIngredientMap(): array
    {
        return ArrayHelper::map(self::findAll(['hidden' => self::NOT_HIDDEN]), 'id', 'name');
    }

}
