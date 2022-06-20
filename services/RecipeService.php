<?php

namespace app\services;

use app\models\Ingredient;
use yii\db\Query;

class RecipeService
{
    /**
     * Метод формирует запрос подсчет общего количества ингредиентов по каждому рецепту.
     * @return Query
     */
    public static function countAllRecipeIngredients(): Query
    {
        return $query = (new Query())
            ->select('COUNT(recipe_id) as all_ingredients, recipe_id')
            ->from('recipe_ingredient')
            ->groupBy('recipe_id');
    }

    /**
     * Метод возвращает массив, состоящий из 'id' рецептов, в составе которых есть скрытый ингредиент
     * @return array
     */
    public static function getRecipesWithHiddenIngredients()
    {
        return $query = (new Query())
            ->select('recipe_id')
            ->from('recipe_ingredient')
            ->rightJoin('ingredient', 'recipe_ingredient.ingredient_id = ingredient.id')
            ->where(['ingredient.hidden' => Ingredient::IS_HIDDEN])->column();
    }

    /**
     * Метод сводит в одном запросе рецепт, общее количество ингредиентов по рецепту и количество совпадений
     * ингредиентов в запросе с ингредиентами по рецпту. Выборка упорядочивается по количество совпадений с ингредиентами
     * - от большего к меньшему.
     * @param Query $countAllRecipeIngredients
     * @param array $ingredientsList
     * @return Query
     */
    public static function countRecipeMatches(array $ingredientsList)
    {
        return $query = (new Query())
            ->select('COUNT(ri.recipe_id) matches, all_ingredients, recipe.*')
            ->from('recipe_ingredient as ri')
            ->leftJoin(['recipe_ingredients' => self::countAllRecipeIngredients()], 'recipe_ingredients.recipe_id = ri.recipe_id')
            ->leftJoin('recipe', 'recipe.id = ri.recipe_id')
            ->where(['in', 'ri.ingredient_id', $ingredientsList])
            ->groupBy('ri.recipe_id')->orderBy(['matches' => SORT_DESC]);
    }

}