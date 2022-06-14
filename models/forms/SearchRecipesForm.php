<?php

namespace app\models\forms;

use app\services\RecipeService;
use yii\data\ActiveDataProvider;
use yii\db\Query;


class SearchRecipesForm extends \yii\base\Model
{
    public array $ingredients_list = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ingredients_list'], 'safe']
        ];
    }

    /**
     * DataProvider для фильтрации рецептов на главной странице.
     * @return ActiveDataProvider
     */
    public function getDataProvider(): ActiveDataProvider
    {
        $query = (new Query());
        if (!$this->ingredients_list) {
            $query = $query->select('recipe.*')->from('recipe');
        }

        if ($this->ingredients_list) {

            //Запрос, направленный на подсчет общего количества ингредиентов по каждому рецепту
            $countAllRecipeIngredients = RecipeService::countAllRecipeIngredients();

            //Запрос, рассчитывающий количество совпадений по ингредиентам в рецептах.
            $countMatches = RecipeService::countRecipeMatches($countAllRecipeIngredients, $this->ingredients_list);

            //Фильтры для выбора полных совпадений по ингредиентам
            $fullMatches = $countMatches
                ->having(['>=', 'matches', 2])
                ->andHaving(['matches' => count($this->ingredients_list)])
                ->andHaving(['<=', 'all_ingredients', count($this->ingredients_list)]);

            if ($fullMatches->exists()) {
                $query = $fullMatches;
            } else {
                //Фильтры для выбора частичных совпадений по ингредиентам
                $partMatches = $countMatches
                    ->orHaving(['<=', 'matches', count($this->ingredients_list)])
                    ->andHaving(['>=', 'matches', 2]);
                $query = $partMatches;
            }

        }

        // Массив из 'id' рецептов, в составе которых есть скрытый ингредиент
        $recipesWithHiddenIngredients = RecipeService::getRecipesWithHiddenIngredients();

        //Исключаем из выборки рецепты со скрытыми ингредиентами.
        $query->andWhere(['not in', 'recipe.id', $recipesWithHiddenIngredients]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}