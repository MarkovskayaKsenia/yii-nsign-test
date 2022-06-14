<?php


namespace app\controllers;

use app\models\forms\CreateIngredientForm;
use app\models\Ingredient;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class IngredientsController extends SecuredController
{
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

    public function actionEdit(int $ingredientId)
    {
        $ingredientModel = new Ingredient();
        $ingredient = Ingredient::findOne($ingredientId);

        if (\Yii::$app->request->isPost) {
            $ingredient->load(\Yii::$app->request->post());
            $ingredient->validate();

            if (!$ingredient->hasErrors()) {
                $ingredient->save();
                return $this->redirect("/ingredient/show/{$ingredient->id}");
            } else {
                $ingredientModel->addErrors($ingredient->getErrors());
            }
        }

        if ($ingredient) {
            return $this->render('edit', [
                'ingredient' => $ingredient,
                'ingredientModel' => $ingredientModel
            ]);
        }
    }

    /**
     * @param int $ingredientId
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionDelete(int $ingredientId)
    {
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