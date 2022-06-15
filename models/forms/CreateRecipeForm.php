<?php


namespace app\models\forms;


use app\models\Recipe;
use app\models\RecipeIngredient;
use yii\base\Model;

class CreateRecipeForm extends Model
{
    public  $ingredients_list;
    public  $title;
    public  $description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'ingredients_list'], 'safe'],
            [['title', 'description'], 'required'],
            ['ingredients_list', 'required', 'message' => 'Укажите ингредиенты для рецепта'],
            ['description', 'string', 'min' => 20, 'tooShort' => "Не меньше {min} символов"],
            ['title', 'string', 'max' => 150, 'min' => 5,
                'tooShort' => "Не меньше {min} символов", 'tooLong' => "Не больше {max} символов"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'description' => 'Описание рецепта',
        ];
    }

    /**
     * Метод, отвечающий за удаление ингредиентов в рецепте на таблице связей
     * @param Recipe $recipe
     * @throws \yii\db\StaleObjectException
     */

    public static function deleteRecipeIngredients(Recipe $recipe)
    {

        foreach ($recipe->recipeIngredients as $ingredient) {
            $ingredient->delete();
        }
    }

    /**
     * Метод, отвечающий за загрузку данных из формы создания/редактирования рецепта в модель рецепта.
     * @param Recipe|null $recipe
     * @return Recipe|null
     */
    public function loadRecipeData(Recipe $recipe = null)
    {
        if (!$recipe) {
            $recipe = new Recipe();
        }

        $recipe->title = $this->title;
        $recipe->description = $this->description;
        $recipe->user_id = \Yii::$app->user->getId();
        return $recipe;
    }

    /**
     * Метод, отвечающий за загрузку ингредиентов рецепта в таблицу связей БД из формы создании/редактировании рецепта.
     * @param Recipe $recipe
     */
    public function loadRecipeIngredientsData(Recipe $recipe)
    {
        foreach ($this->ingredients_list as $ingredientData) {
            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->recipe_id = $recipe->id;
            $recipeIngredient->ingredient_id = $ingredientData;
            $recipeIngredient->save();
        }
    }

}