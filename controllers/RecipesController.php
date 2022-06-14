<?php


namespace app\controllers;


use app\models\forms\CreateRecipeForm;
use app\models\forms\SearchRecipesForm;
use app\models\Recipe;
use yii\db\Exception;
use yii\db\StaleObjectException;

use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class RecipesController extends SecuredController
{
    public function actionIndex()
    {
       // $recipes = Recipe::find()->all();
        $searchRecipesForm = new SearchRecipesForm();
        $searchRecipesForm->load(\Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $searchRecipesForm->getDataProvider(),
           // 'recipes' => $recipes,
            'searchRecipesForm' => $searchRecipesForm
        ]);
    }

    public function actionShow(int $recipeId)
    {
        $recipe = Recipe::findOne($recipeId);
        if (!$recipe) {
            throw new NotFoundHttpException('Такой рецепт не существует');
        }
        return $this->render('show', ['recipe' => $recipe]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws StaleObjectException
     */
    public function actionCreate()
    {
        $createRecipeForm = new CreateRecipeForm();

        if (\Yii::$app->request->isPost) {
            $createRecipeForm->load(\Yii::$app->request->post());
            $createRecipeForm->validate();

            if ($createRecipeForm->hasErrors()) {
                return $this->render('create', ['createRecipeForm' => $createRecipeForm]);
            }

            $recipe = $createRecipeForm->loadRecipeData();

            if (!$recipe->validate()) {
                throw new BadRequestHttpException("Не удалось сохранить рецепт {$recipe->title}");
            }

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $recipe->insert();
                $createRecipeForm->loadRecipeIngredientsData($recipe);
                $transaction->commit();

                return $this->redirect("/recipe/show/{$recipe->id}");

            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new BadRequestHttpException("Не удалось сохранить рецепт {$recipe->title}");
            }
        }

        return $this->render('create', ['createRecipeForm' => $createRecipeForm]);
    }

    /**
     * @param int $recipeId
     * @return string
     * @throws \Exception
     */
    public function actionEdit(int $recipeId)
    {
        $createRecipeForm = new CreateRecipeForm();
        $recipe = Recipe::findOne($recipeId);

        if (\Yii::$app->request->isPost) {
            $createRecipeForm->load(\Yii::$app->request->post());
            $createRecipeForm->validate();

            if ($createRecipeForm->hasErrors()) {
                return $this->render('edit', [
                    'recipe' => $recipe,
                    'createRecipeForm' => $createRecipeForm,
                ]);
            }

            $recipe = $createRecipeForm->loadRecipeData($recipe);

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $recipe->update();
                CreateRecipeForm::deleteRecipeIngredients($recipe);
                $createRecipeForm->loadRecipeIngredientsData($recipe);
                $transaction->commit();

                return $this->redirect("/recipe/show/{$recipe->id}");
            } catch (StaleObjectException $e) {
                $transaction->rollBack();
                throw new BadRequestHttpException("Не удалось отредактировать рецепт {$recipe->title}");
            }
        }

        return $this->render('edit', [
            'recipe' => $recipe,
            'createRecipeForm' => $createRecipeForm,
        ]);
    }

    /**
     * @param int $recipeId
     * @throws BadRequestHttpException
     */
    public function actionDelete(int $recipeId)
    {
        $recipe = Recipe::findOne($recipeId);
        if ($recipe) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                CreateRecipeForm::deleteRecipeIngredients($recipe);
                $recipe->delete();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new BadRequestHttpException('Не удалось удалить рецепт');
            }
        }

        $this->redirect('/');
    }
}