<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m220606_114530_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->unsigned(),
            'login' => $this->string(30)->notNull()->unique(),
            'password_hash' => $this->char(64)->notNull(),
            'role' => $this->string(10)->notNull()->defaultValue('user'),
            'reg_date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'last_visit_date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
