<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%recipe}}`.
 */
class m220606_151101_create_recipe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe}}', [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string(150)->notNull(),
            'description' => $this->text()->notNull(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'reg_date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->addForeignKey(
            'fk-recipe-user_id-user-id',
            'recipe',
            'user_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-recipe-user_id-user-id',
            'recipe'
        );

        $this->dropTable('{{%recipe}}');
    }
}
