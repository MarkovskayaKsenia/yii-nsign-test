<?php
/**
 * @var \app\models\Ingredient $ingredient
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Просмотр ингредиента';
?>
<h2><?= $this->title ?></h2>
<p><?= Html::encode($ingredient->name); ?></p>

<?= Html::beginForm(["/ingredient/delete/{$ingredient->id}"], 'post', ['class' => 'form-inline d-inline-block'])
. Html::submitButton(
    'Удалить',
    ['class' => 'btn btn-danger mr-4']
)
. Html::endForm();
?>

<a class="btn btn-primary d-inline-block" href="<?= Url::to("/ingredient/edit/{$ingredient->id}"); ?>">Изменить</a>
