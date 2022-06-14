<?php

namespace app\models\forms;

use app\models\Ingredient;
use app\models\Recipe;
use app\models\RecipeIngredient;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class SearchRecipesForm extends \yii\base\Model
{
    public $ingredients_list = [];

    public function attributeLabels()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ingredients_list'], 'safe']
        ];
    }

    public function getDataProvider()
    {
        $query = (new Query());
        if (!$this->ingredients_list) {
            $query = $query->select('recipe.*')->from('recipe');
        }

        if ($this->ingredients_list) {

            $recipeIngredients = (new Query())
                ->select('COUNT(recipe_id) as all_ingredients, recipe_id')
                ->from('recipe_ingredient')
                ->groupBy('recipe_id');


            $countMatches = $query
                ->select('COUNT(ri.recipe_id) matches, all_ingredients, recipe.*')
                ->from('recipe_ingredient as ri')
                ->leftJoin(['recipe_ingredients' => $recipeIngredients], 'recipe_ingredients.recipe_id = ri.recipe_id')
                ->leftJoin('recipe', 'recipe.id = ri.recipe_id')
                ->where(['in', 'ri.ingredient_id', $this->ingredients_list])
                ->groupBy('ri.recipe_id')->orderBy(['matches' => SORT_DESC]);

            $fullMatches = $countMatches
                ->having(['>=', 'matches', 2])
                ->andHaving(['matches' => count($this->ingredients_list)])
                ->andHaving(['<=', 'all_ingredients', count($this->ingredients_list)]);


            if ($fullMatches->exists()) {
                $query = $fullMatches;
            } else {
                $partMatches = $countMatches
                    ->orHaving(['<=', 'matches', count($this->ingredients_list)])
                    ->andHaving(['>=', 'matches', 2]);
                $query = $partMatches;
            }

        }

        $recipesWithHiddenIngredients = (new Query())
            ->select('recipe_id')
            ->from('recipe_ingredient')
            ->rightJoin('ingredient', 'recipe_ingredient.ingredient_id = ingredient.id')
            ->where(['ingredient.hidden' => Ingredient::IS_HIDDEN])->column();

        $query->andWhere(['not in', 'recipe.id', $recipesWithHiddenIngredients]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}