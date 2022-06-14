<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%recipe_ingredient}}`.
 */
class m220606_151143_create_recipe_ingredient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe_ingredient}}', [
            'id' => $this->primaryKey()->unsigned(),
            'recipe_id' => $this->integer()->unsigned()->notNull(),
            'ingredient_id' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-recipe_ingredient-recipe_id-recipe-id',
            'recipe_ingredient',
            'recipe_id',
            'recipe',
            'id'
        );

        $this->addForeignKey(
            'fk-recipe_ingredient-ingredient_id-ingredient-id',
            'recipe_ingredient',
            'ingredient_id',
            'ingredient',
            'id'
        );

        $this->createIndex(
            'recipe_id-ingredient_id',
            'recipe_ingredient',
            ['recipe_id', 'ingredient_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropIndex(
            'recipe_id-ingredient_id',
            'recipe_ingredient'
        );

        $this->dropForeignKey(
            'fk-recipe_ingredient-recipe_id-recipe-id',
            'recipe_ingredient'
        );

        $this->dropForeignKey(
            'fk-recipe_ingredient-ingredient_id-ingredient-id',
            'recipe_ingredient'
        );

        $this->dropTable('{{%recipe_ingredient}}');
    }
}
