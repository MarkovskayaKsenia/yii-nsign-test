<?php


namespace app\models\forms;


use app\models\Ingredient;
use yii\base\Model;

class CreateIngredientForm extends Model
{
    public $name;
    public $hidden;

    public function rules()
    {
        return [
            [['name', 'hidden'], 'safe'],
            [['name'], 'required'],
            [['hidden'], 'integer', 'min' => 0, 'max' => 1],
            ['name', 'string', 'max' => 25, 'min' => 2,
                'tooShort' => "Не меньше {min} символов", 'tooLong' => "Не больше {max} символов"],
           // ['name', 'isNameUnique'],
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

    public function loadIngredientData(Ingredient $ingredient = null)
    {
        if (!$ingredient) {
            $ingredient = new Ingredient();
        }
        $ingredient->name = $this->name;
        $ingredient->hidden = $this->hidden;
        return $ingredient;
    }

    public function isNameUnique($attribute)
    {
        $ingredient = Ingredient::find()->where([$attribute => $this->$attribute])->one();
        if ($ingredient) {
            $this->addError($attribute, 'Такой ингредиент уже существует');
        }

    }

}