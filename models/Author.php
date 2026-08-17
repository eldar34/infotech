<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    /**
     * @var AuthorSubscription|null
     */
    public ?AuthorSubscription $subscriptionModel = null;

    public static function tableName()
    {
        return '{{%author}}';
    }

    public function rules()
    {
        return [['full_name', 'required'], ['full_name', 'string', 'max' => 255]];
    }

    // Связь с книгами через промежуточную таблицу
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('{{%book_author}}', ['author_id' => 'id']);
    }

    /**
     * Связь с подписчиками автора
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(AuthorSubscription::class, ['author_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC]);
    }
}
