<?php

/** @var yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\forms\SearchRecipesForm $searchRecipesForm
 */

use app\assets\CheckboxesAsset;
use app\models\Ingredient;


use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

CheckboxesAsset::register($this);
$this->title = 'Список рецептов';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4"><?= $this->title; ?></h1>
    </div>
    <div class="body-content d-flex ">
        <div class="row w-75">
            <?php if (!empty($dataProvider->getModels())): ?>
                <?php foreach ($dataProvider->getModels() as $recipe): ?>

                    <div class="card border-secondary mb-3 w-25 mr-2 align-content-start">
                        <div class="card-header font-weight-bold"><a
                                    href="<?= Url::to("recipe/show/{$recipe['id']}") ?>"><?= Html::encode($recipe['title']); ?></a>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text"><?= Html::encode($recipe['description']); ?></p>
                        </div>
                        <?php if (array_key_exists('matches', $recipe)): ?>
                        <p class="content p-2"><?= "Совпадений по ингредиентам: {$recipe['matches']}</p>"; ?>
                            <?php endif; ?>
                            <a class="btn btn-primary"
                               href="<?= Url::to("recipe/show/{$recipe['id']}") ?>">Просмотр</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="mb-3 w-75 mr-2">
                    <p class="font-weight-bold text-uppercase text-danger">Ничего не найдено!</p>
                </div>
            <?php endif; ?>
        </div>

        <section class="w-25">
            <?php $form = ActiveForm::begin(['action' => ['index'],
                'method' => 'get',
                'fieldConfig' => ['checkTemplate' => "{beginLabel}\n{input}<span>{labelTitle}</span>{endLabel}",
                    'checkOptions' => ['class' => 'visually-hidden',],
                    'options' => ['tag' => false,]],
                'enableAjaxValidation' => false,]); ?>

            <fieldset class="list-group">
                <h4 class="border-bottom border-dark text-primary">Поиск рецептов по ингредиентам</h4>
                <?= $form->field($searchRecipesForm, 'ingredients_list', ['template' => "{input}",
                    'options' => ['tag' => false, 'class' => 'list-group']])
                    ->checkboxList(Ingredient::getNotHiddenIngredientMap(),
                        ['item' => function ($index, $label, $name, $checked, $value) {
                            return '<label class="form-check-label d-block">'
                                . Html::checkbox($name, $checked, ['value' => $value,
                                    'class' => 'visually-hidden mr-2 mb-2 ingredient-checkbox'])
                                . '<span>' . $label . '</span></label>';
                        }]); ?>
            </fieldset>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary mt-2', 'id' => 'search-recipe-button']); ?>
            <?php ActiveForm::end(); ?>
            <a class="btn btn-primary mt-2" href="<?= Url::to('/'); ?>">Сбросить фильтры</a>
        </section>
    </div>


