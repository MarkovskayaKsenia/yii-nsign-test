<?php
/**
 * @var \app\models\Ingredient $ingredient
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Просмотр ингредиента';
?>
<h2><?= $this->title?></h2>
<p><?= Html::encode($ingredient->name); ?></p>
<a class="btn btn-danger" href="<?= Url::to("/ingredient/delete/{$ingredient->id}"); ?>">Удалить</a>
<a class="btn btn-primary" href="<?= Url::to("/ingredient/edit/{$ingredient->id}"); ?>">Изменить</a>
