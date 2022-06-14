<?php
/**
 * @var array $ingredients
 * @var \app\models\Ingredient $ingredient
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Список ингредиентов';
?>

<h2><?= $this->title; ?></h2>

<table class="table table-bordered table-hover">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Ингредиент</th>
        <th scope="col">Скрыт</th>
        <th scope="col">Удалить</th>
        <th scope="col">Редактировать</th>
        <th scope="col">Просмотр</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($ingredients as $key => $ingredient): ?>
    <tr class="<?= $ingredient->hidden ? 'table-warning' : ''; ?> ">
        <th scope="row"><?= $key + 1; ?></th>
        <td><a class="d-block font-weight-bold text-dark" href="<?= Url::toRoute(['/ingredients/show', 'ingredientId' => $ingredient->id]); ?>">
                <?= Html::encode($ingredient->name); ?>
            </a>
        </td>
        <td><?= $ingredient->hidden ? "Да" : "Нет"; ?></td>
        <td><a class="btn btn-outline-danger" href="<?= Url::toRoute(['/ingredients/delete', 'ingredientId' => $ingredient->id]); ?>">Удалить</a></td>
        <td><a class="btn btn-outline-secondary" href="<?= Url::toRoute(['/ingredients/edit', 'ingredientId' => $ingredient->id]); ?>">Редактировать</a></td>
        <td><a class="btn btn-outline-secondary" href="<?= Url::toRoute(['/ingredients/show', 'ingredientId' => $ingredient->id]); ?>">Просмотр</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a class="btn btn-primary" href="<?= Url::to('/ingredient/create')?>">Добавить ингредиент</a>