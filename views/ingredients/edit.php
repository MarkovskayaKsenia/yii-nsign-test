<?php
/**
 * @var \app\models\forms\CreateIngredientForm $createIngredientForm
 * @var \app\models\Ingredient $ingredient
 */

use \yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Редактирование ингредиента';
?>

<h2 class="text-primary"><?= $this->title; ?></h2>

<?php $form = ActiveForm::begin([
    'action' => ["ingredient/edit/{$ingredient->id}"],
    'method' => 'post',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'errorOptions' => ['tag' => 'span', 'class' => 'is-invalid'],
        'checkTemplate' => "{beginLabel}\n{input}<span>{labelTitle}</span>{endLabel}",
        'checkOptions' => [
            'class' => 'visually-hidden',
        ],
        'options' => [
            'tag' => 'div',
        ]],
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
]); ?>

<fieldset>
    <?= $form->field($createIngredientForm, 'name')
        ->textInput([
            'class' => 'form-control d-block mb-2',
            'value' => Html::encode($ingredient->name),
        ])->error(['class' => 'is-invalid text-danger']); ?>

    <?= $form->field($createIngredientForm, 'hidden')->checkbox([
        'class' => 'mr-2',
        'checked' => !!$ingredient->hidden
    ]); ?>

    <?= $form->field($createIngredientForm, 'id')->hiddenInput(['value' => $ingredient->id])->label(false); ?>
</fieldset>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>

<?php ActiveForm::end() ?>
