<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use app\forms\BookForm;
use yii\web\ServerErrorHttpException;

/**
 * @property int $id
 * @property string $title
 * @property int $release_year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $cover_image
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    public const EVENT_BOOK_CREATED = 'bookCreated';

    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'release_year', 'isbn'], 'required'],
            [['release_year'], 'integer'],
            [['description'], 'string'],
            [['title', 'isbn', 'cover_image'], 'string', 'max' => 255],
        ];
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    /**
     * Гидратация модели данными из формы
     */
    public function hydrate(BookForm $form, ?string $coverImage): void
    {
        $this->title = $form->title;
        $this->release_year = $form->release_year;
        $this->description = $form->description;
        $this->isbn = $form->isbn;
        $this->cover_image = $coverImage;
    }

    /**
     * Привязка массива ID авторов к книге
     */
    public function linkAuthors(array $authorIds): void
    {
        foreach ($authorIds as $authorId) {
            $author = Author::findOne($authorId);
            if ($author !== null) {
                $this->link('authors', $author);
            }
        }
    }

    public function createWithAuthors(BookForm $form, ?string $coverImage): bool
    {
        $transaction = self::getDb()->beginTransaction();
        try {
            $this->title = $form->title;
            $this->release_year = $form->release_year;
            $this->description = $form->description;
            $this->isbn = $form->isbn;
            $this->cover_image = $coverImage;

            if (!$this->save()) {
                throw new ServerErrorHttpException('Не удалось сохранить книгу.');
            }

            // Связываем авторов
            foreach ($form->authorIds as $authorId) {
                $author = Author::findOne($authorId);
                if ($author !== null) {
                    $this->link('authors', $author);
                }
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function updateWithAuthors(BookForm $form, ?string $coverImage): bool
    {
        $transaction = self::getDb()->beginTransaction();
        try {
            $this->title = $form->title;
            $this->release_year = $form->release_year;
            $this->description = $form->description;
            $this->isbn = $form->isbn;
            if ($coverImage !== null) {
                $this->cover_image = $coverImage;
            }

            if (!$this->save()) {
                throw new ServerErrorHttpException('Не удалось обновить книгу.');
            }

            // Перезаписываем связи: удаляем старые, привязываем новые
            $this->unlinkAll('authors', true);
            $this->linkAuthors($form->authorIds);

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
