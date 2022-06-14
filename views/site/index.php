<?php

/** @var yii\web\View $this
 * @var array $recipes
 * @var Recipe $recipe
 */

use app\models\Ingredient;
use app\models\Recipe;
use yii\bootstrap4\ActiveForm;

$this->title = 'Список рецептов';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Рецепты на все времена!</h1>
    </div>

        <div class="body-content d-flex align-content-start">
            <div class="row">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="card border-primary mb-3 col-lg-3 mr-2">
                        <h2><a href="#"><?= $recipe->title; ?></a></h2>

                        <p><?= $recipe->description; ?></p>

                    </div>
                <?php endforeach; ?>
            </div>

        <section class="">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'search-task__form'],
                'fieldConfig' => [
                    'checkTemplate' => "{beginLabel}\n{input}<span>{labelTitle}</span>{endLabel}",
                    'checkOptions' => [
                        'class' => 'visually-hidden',
                    ],
                    'options' => [
                        'tag' => false,
                    ]],
                'enableAjaxValidation' => false,
            ]); ?>

            <fieldset class="list-group">
                <legend>Ингредиенты</legend>
                <?= $form->field($searchRecipesModel, 'ingredients', [
                    'template' => "{input}",
                    'options' => ['tag' => false, 'class' => 'list-group']
                ])
                    ->checkboxList(Ingredient::getIngredientMap(),
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<label class="list-group-item d-block">' . \yii\helpers\Html::checkbox($name, $checked, ['value' => $value, 'class' => 'visually-hidden mr-1'])
                                .'<span>' . $label. '</span></label>';
                            }
                        ]); ?>
            </fieldset>
            <?php ActiveForm::end(); ?>
        </section>

</div>
