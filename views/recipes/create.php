<?php
/**
 * @var \app\models\Recipe $recipe
 * @var \app\models\forms\CreateRecipeForm $createRecipeForm
 */

use app\models\Ingredient;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Создать рецепт';
?>

<h2 class="text-primary"><?= $this->title; ?></h2>

<?php $form = ActiveForm::begin([
    'action' => ["recipe/create"],
    'method' => 'post',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'errorOptions' => ['tag' => 'span', 'class' => 'is-invalid'],
        'checkTemplate' => "{beginLabel}\n{input}<span>{labelTitle}</span>{endLabel}",
        'checkOptions' => [
            'class' => 'visually-hidden',
        ],
        'options' => [
            'tag' => 'div'
        ]],
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
]); ?>

<fieldset>

    <?= $form->field($createRecipeForm, 'title')
        ->textInput([
            'class' => 'form-control d-block mb-3',
        ])->error(['class' => 'is-invalid text-danger']); ?>

    <?= $form->field($createRecipeForm, 'description')
        ->textarea([
            'rows' => 10,
            'class' => 'form-control d-block mb-3',
        ])->error(['class' => 'is-invalid text-danger']); ?>
</fieldset>

<fieldset>
    <legend class="font-weight-bold">Ингредиенты для приготовления</legend>
    <?= $form->field($createRecipeForm, 'ingredients_list', [
        'template' => "{input}",
        'options' => ['tag' => 'div', 'class' => 'd-flex']
    ])
        ->checkboxList(Ingredient::getNotHiddenIngredientMap(),
            [
                'item' => function ($index, $label, $name, $checked, $value) {
                    return '<label class="form-check-label col-3">' . Html::checkbox($name, $checked, ['value' => $value, 'class' => 'visually-hidden mr-2 mb-2'])
                        . '<span>' . $label . '</span></label>';
                }
            ]); ?>
    <?= Html::error($createRecipeForm, 'ingredients_list', ['class' => 'text-danger']); ?>
</fieldset>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary mt-5']); ?>

<?php ActiveForm::end() ?>
