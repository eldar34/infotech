<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class AuthorSubscription extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%author_subscription}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'email'], 'required'],
            [['author_id'], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'email'], // Проверка корректности формата почты

            // Проверяем, существует ли вообще автор с таким ID в базе данных
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Author::class,
                'targetAttribute' => ['author_id' => 'id'],
                'message' => 'Выбранный автор не существует.'
            ],

            // Защита от дубликатов подписок (уникальный индекс на уровне модели)
            [
                ['email', 'author_id'],
                'unique',
                'targetAttribute' => ['email', 'author_id'],
                'message' => 'Вы уже подписаны на обновления этого автора.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'email' => 'Ваш Email',
            'created_at' => 'Дата подписки',
        ];
    }

    /**
     * Связь с моделью Автора
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
