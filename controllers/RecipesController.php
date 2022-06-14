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
    /**
     * Метод, отвечающий за отображение главной страницы с рецептами
     * @return string
     */
    public function actionIndex()
    {
        $searchRecipesForm = new SearchRecipesForm();
        $searchRecipesForm->load(\Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $searchRecipesForm->getDataProvider(),
            'searchRecipesForm' => $searchRecipesForm
        ]);
    }

    /**
     * Метод, отвечающий за отображение страницы отдельно взятого рецепта
     * @param int $recipeId
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionShow(int $recipeId)
    {
        $recipe = Recipe::findOne($recipeId);
        if (!$recipe) {
            throw new NotFoundHttpException('Такой рецепт не существует');
        }
        return $this->render('show', ['recipe' => $recipe]);
    }

    /**
     * Метод, отвечающий за отображение странцы создания рецепта и обработку данных, пришедших из формы.
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
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

            //Загрузка данных из формы в модель рецепта
            $recipe = $createRecipeForm->loadRecipeData();

            if (!$recipe->validate()) {
                throw new BadRequestHttpException("Не удалось сохранить рецепт {$recipe->title}");
            }

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $recipe->insert();
                //Загрузка списка ингредиентов в модель таблицы связей и сохранение в БД
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
     * Метод, отвечающий за отображение страницы редактирования рецепта и обработку данных из формы.
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
     * Метод, отвечающий за удаление рецепта из БД.
     * @param int $recipeId
     * @throws BadRequestHttpException
     */
    public function actionDelete(int $recipeId)
    {
        if (!\Yii::$app->request->isPost) {
            $this->redirect('/');
        }

        $recipe = Recipe::findOne($recipeId);
        if ($recipe) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                CreateRecipeForm::deleteRecipeIngredients($recipe);
                $recipe->delete();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new BadRequestHttpException('Не удалось удалить рецепт');
            }
        }

        $this->redirect('/');
    }
}