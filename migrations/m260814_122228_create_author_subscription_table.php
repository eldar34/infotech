<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_subscription}}`.
 */
class m260814_122228_create_author_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'email' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Внешний ключ на автора
        $this->addForeignKey(
            'fk-subscription-author',
            '{{%author_subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );

        // Уникальный индекс, запрещающий дублирование подписки
        $this->createIndex(
            'idx-subscription-email-author',
            '{{%author_subscription}}',
            ['email', 'author_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-subscription-email-author', '{{%author_subscription}}');
        $this->dropForeignKey('fk-subscription-author', '{{%author_subscription}}');
        $this->dropTable('{{%author_subscription}}');
    }
}
