<?php


namespace app\controllers;

use app\models\forms\CreateIngredientForm;
use app\models\Ingredient;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class IngredientsController extends SecuredController
{
    /**
     * Метод, отвечающий за отображение страницы со списком всех ингредиентов
     * @return string
     */
    public function actionIndex()
    {
        $ingredients = Ingredient::find()->all();
        return $this->render('index', ['ingredients' => $ingredients]);
    }

    public function actionShow(int $ingredientId)
    {
        $ingredient = Ingredient::findOne($ingredientId);
        if (!$ingredient) {
            throw new NotFoundHttpException('Такой ингредиент не существует!');
        }
        return $this->render('show', ['ingredient' => $ingredient]);
    }

    /**
     * Метод, отвечающий за отображение страницы создания ингредиента и обработку данных с формы.
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        $createIngredientForm = new CreateIngredientForm();

        if (\Yii::$app->request->isPost) {
            $createIngredientForm->load(\Yii::$app->request->post());
            $createIngredientForm->validate();

            if ($createIngredientForm->hasErrors()) {
                return $this->render('create', ['createIngredientForm' => $createIngredientForm]);
            }

            $ingredient = $createIngredientForm->loadIngredientData();

            if ($ingredient->save()) {
                return $this->redirect("/ingredient/show/{$ingredient->id}");
            } else {
                throw new BadRequestHttpException("Не удалось добавить ингредиент {$createIngredientForm->name} в список");
            }
        }
        return $this->render('create', ['createIngredientForm' => $createIngredientForm]);
    }

    /**
     * Метод, отвечающий за отображение формы редактирования ингредиента и обработку данных из формы.
     * @param int $ingredientId
     * @return string|\yii\web\Response
     */
    public function actionEdit(int $ingredientId)
    {
        $createIngredientForm = new CreateIngredientForm();
        $ingredient = Ingredient::findOne($ingredientId);

        if (\Yii::$app->request->isPost) {
            $createIngredientForm->load(\Yii::$app->request->post());
            $createIngredientForm->validate();

            if ($createIngredientForm->hasErrors()) {
                return $this->render('edit', [
                'ingredient' => $ingredient,
                'createIngredientForm' => $createIngredientForm
            ]);
            }

            $ingredient = $createIngredientForm->loadIngredientData($ingredient);

            if ($ingredient->save()) {
                return $this->redirect("/ingredient/show/{$ingredient->id}");
            } else {
                $createIngredientForm->addErrors($ingredient->getErrors());
            }
        }

        if ($ingredient) {
            return $this->render('edit', [
                'ingredient' => $ingredient,
                'createIngredientForm' => $createIngredientForm
            ]);
        }
    }

    /**
     * Метод, отвечающий за удаление ингредиента из списка ингредиентов в БД.
     * @param int $ingredientId
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionDelete(int $ingredientId)
    {
        if (!\Yii::$app->request->isPost) {
            return $this->redirect('/ingredients');
        }

        $ingredient = Ingredient::findOne($ingredientId);
        if ($ingredient) {
            try {
                $ingredient->delete();
                return $this->redirect('/ingredients');
            } catch (\Exception $e) {
                throw new HttpException(409, "Ингредиент используется в рецепте. Удаление невозможно");
            }
        }
    }

}