<?php


namespace app\models\forms;


use app\models\Ingredient;
use yii\base\Model;

class CreateIngredientForm extends Model
{
    public $id;
    public $name;
    public $hidden;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'hidden', 'id'], 'safe'],
            [['name'], 'required'],
            [['hidden'], 'integer', 'min' => 0, 'max' => 1],
            ['name', 'string', 'max' => 25, 'min' => 2,
                'tooShort' => "Не меньше {min} символов", 'tooLong' => "Не больше {max} символов"],
            ['name', 'isNameUnique'],
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
     * Загрузка данных из формы в модель Ingredient.
     * @param Ingredient|null $ingredient
     * @return Ingredient|null
     */
    public function loadIngredientData(Ingredient $ingredient = null)
    {
        if (!$ingredient) {
            $ingredient = new Ingredient();
        }
        $ingredient->name = $this->name;
        $ingredient->hidden = $this->hidden;
        return $ingredient;
    }

    /**
     * Метод проверки ингредиента на уникальность в БД.
     * @param $attribute
     */
    public function isNameUnique($attribute)
    {
        $ingredient = Ingredient::find()->where(['name' => $this->name])->one();
        if ($ingredient  && !$ingredient->id = $this->id) {
            $this->addError($attribute, 'Такой ингредиент уже существует');
        }
    }

}