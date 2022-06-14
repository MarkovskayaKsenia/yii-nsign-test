<?php
/**
 * @var \app\models\Recipe $recipe
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Просмотр рецептов';
?>
<h2 class="text-primary mb-3"><?= $this->title; ?></h2>
<h3 class="mb-3"><?= Html::encode($recipe->title); ?></h3>
<p class="border border-info p-2"><?= Html::encode($recipe->description); ?> </p>
<h4 class="text-primary">Список ингредиентов:</h4>
<?= Html::ol(ArrayHelper::map($recipe->recipeIngredientsList, 'id', 'name')); ?>

<a class="btn btn-danger " href="<?= Url::to("/recipe/delete/{$recipe->id}"); ?>">Удалить</a>
<a class="btn btn-primary" href="<?= Url::to("/recipe/edit/{$recipe->id}"); ?>">Редактировать</a>