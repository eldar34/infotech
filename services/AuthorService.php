<?php

declare(strict_types=1);

namespace app\services;

use app\models\Author;
use app\models\AuthorSubscription;
use app\forms\AuthorForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class AuthorService
{
    /**
     * Получение провайдера данных для списка авторов.
     */
    public function getProvider(int $pageSize = 20): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Author::find(),
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'full_name' => SORT_ASC,
                ],
            ],
        ]);
    }

    /**
     * Поиск модели автора по ID.
     * 
     * @throws NotFoundHttpException
     */
    public function findModel(int $id): Author
    {
        if (($model = Author::find()->where(['id' => $id])->with(['books', 'subscriptions'])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый автор не найден.');
    }

    /**
     * Создание нового автора.
     */
    public function create(AuthorForm $form, array $postData): ?Author
    {
        if ($form->load($postData) && $form->validate()) {
            $model = new Author();
            $model->full_name = $form->full_name;

            if ($model->save(false)) { // false, так как форма уже все отвалидировала
                Yii::$app->session->setFlash('success', 'Автор успешно добавлен.');
                return $model;
            }
        }
        return null;
    }

    public function populateForm(Author $model, AuthorForm $form): void
    {
        $form->id = $model->id;
        $form->full_name = $model->full_name;
    }

    /**
     * Обновление данных автора.
     */
    public function update(Author $model, AuthorForm $form, array $postData): bool
    {
        if ($form->load($postData) && $form->validate()) {
            $model->full_name = $form->full_name;

            if ($model->save(false)) { // false, так как форма уже все отвалидировала
                Yii::$app->session->setFlash('success', 'Данные автора обновлены.');
                return true;
            }
        }
        return false;
    }

   /**
     * Получение провайдера данных для Книг автора
     * 
     * @param Author $author
     * @return ActiveDataProvider
     */
    public function getBookProvider(Author $author): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $author->getBooks(),
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'book-page',
            ],
        ]);
    }

    /**
     * Создает и инициализирует модель подписки для автора.
     */
    public function createSubscriptionModel(int $authorId): AuthorSubscription
    {
        $model = new AuthorSubscription();
        $model->author_id = $authorId;

        // Если пользователь авторизован, автоматически предзаполняем его Email
        if (!Yii::$app->user->isGuest) {
            $model->email = Yii::$app->user->identity->email;
        }

        return $model;
    }    

    /**
     * Удаление автора.
     */
    public function delete(Author $model): void
    {
        $model->delete();
        Yii::$app->session->setFlash('success', 'Автор удален из системы.');
    }

    /**
     * Возвращает индексированный массив имён авторов по их ID.
     * 
     * @param int[] $ids Массив идентификаторов авторов.
     * @return array<int, string> Массив вида [1 => 'Имя Автора', 2 => 'Имя Второго Автора']
     */
    public function getListByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return Author::find()
            ->select(['full_name'])
            ->where(['id' => $ids])
            ->indexBy('id')
            ->column();
    }

    public function searchAuthors(?string $q, int $limit = 50): array
    {
        $query = Author::find()->select(['id', 'full_name AS text']);

        if (!empty($q)) {
            $query->where(['like', 'full_name', $q]);
        }

        return $query->limit($limit)->asArray()->all();
    }
}
