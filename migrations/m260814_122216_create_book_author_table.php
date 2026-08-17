<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m260814_122216_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY ([[book_id]], [[author_id]])',
        ]);

        // Внешний ключ для связи с таблицей книг
        $this->addForeignKey(
            'fk-book_author-book',
            '{{%book_author}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE'
        );

        // Внешний ключ для связи с таблицей авторов
        $this->addForeignKey(
            'fk-book_author-author',
            '{{%book_author}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-book_author-author', '{{%book_author}}');
        $this->dropForeignKey('fk-book_author-book', '{{%book_author}}');
        $this->dropTable('{{%book_author}}');
    }
}
