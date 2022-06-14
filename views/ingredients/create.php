<?php
/**
 * @var \app\models\forms\CreateIngredientForm $createIngredientForm
 */

use \yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Создание ингредиента';
?>

<h2 class="text-primary"><?= $this->title; ?></h2>

<?php $form = ActiveForm::begin([
    'action' => ['create'],
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
        'class' => 'form-control d-block mb-3',
        'placeholder' => 'От 2 до 25 символов'
    ])->error(['class' => 'is-invalid text-danger']); ?>

    <?= $form->field($createIngredientForm, 'hidden')->checkbox(['class' => 'mr-2 mb-3']); ?>
</fieldset>

<?= Html::submitButton('Создать', ['class' => 'btn btn-primary']); ?>

<?php ActiveForm::end() ?>
