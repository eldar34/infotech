<?php

declare(strict_types=1);

namespace app\services;

use app\events\BookEvent;
use app\forms\BookForm;
use app\models\Book;
use app\models\AuthorSubscription;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class BookService
{
    // Внедряем сервис загрузки картинок
    public function __construct(
        private readonly ImageUploadService $imageService
    ) {}

    public function getProvider(int $pageSize = 12): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Book::find()->with('authors'),
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }

    public function create(BookForm $form): ?Book
    {
        $book = new Book();
        $coverImage = $this->imageService->upload($form->imageFile);

        if ($book->createWithAuthors($form, $coverImage)) {

            // Устанавливаем flash-сообщение сессии
            Yii::$app->session->addFlash('success', 'Книга успешно добавлена.');
            
            // Триггерим событие успешного создания
            $event = new BookEvent();
            $event->book = $book;
            $book->trigger(Book::EVENT_BOOK_CREATED, $event);
   
            return $book;
        }

        return null;
    }

    public function populateForm(Book $book, BookForm $form): void
    {
        $form->bookId = $book->id;
        $form->title = $book->title;
        $form->release_year = $book->release_year;
        $form->description = $book->description;
        $form->isbn = $book->isbn;
        $form->authorIds = ArrayHelper::map($book->authors, 'id', 'id');
    }

    public function findModel(int $id): Book
    {
        /** @var Book|null $model */
        $model = Book::find()->where(['id' => $id])->with('authors')->one();

        if ($model !== null) {
            // Добавляем модель подписки
            foreach ($model->authors as $author) {
                $subModel = new AuthorSubscription();
                $subModel->author_id = $author->id;

                // Если пользователь авторизован, сразу подставляем его email
                if (!Yii::$app->user->isGuest) {
                    $subModel->email = Yii::$app->user->identity->email;
                }

                $author->subscriptionModel = $subModel;
            }

            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая книга не найдена.');
    }

    public function update(Book $book, BookForm $form): bool
    {
        $coverImage = null;
        
        // Если загружен новый файл обложки
        if ($form->imageFile !== null) {
            $coverImage = $this->imageService->upload($form->imageFile);
            $this->imageService->delete($book->cover_image); // Удаляем старую обложку
        }

        // Обновляем данные 
        if ($book->updateWithAuthors($form, $coverImage)) {
            Yii::$app->session->setFlash('success', 'Данные книги обновлены.');
            return true;
        }

        return false;
    }

    public function delete(Book $book): void
    {
        $transaction = Book::getDb()->beginTransaction();
        try {
            // Удаляем файл обложки через сервис картинок
            $this->imageService->delete($book->cover_image);
            
            // Отвязываем авторов
            $book->unlinkAll('authors', true);
            
            // Удаляем саму запись из базы данных
            if (!$book->delete()) {
                throw new ServerErrorHttpException('Ошибка удаления книги.');
            }
            
            $transaction->commit();
            
            // Устанавливаем флеш-сообщение об успешном удалении
            Yii::$app->session->setFlash('success', 'Книга удалена.');
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
