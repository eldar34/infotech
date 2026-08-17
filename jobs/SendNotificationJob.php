<?php

declare(strict_types=1);

namespace app\jobs;

use app\models\AuthorSubscription;
use app\models\Book;
use Yii;
use yii\base\BaseObject;
use yii\mail\MailerInterface;
use yii\queue\JobInterface;

class SendNotificationJob extends BaseObject implements JobInterface
{
    /** @var int ID созданной книги */
    public int $bookId;

    /**
     * Этот метод вызывается воркером очереди при обработке задачи
     */
    public function execute($queue): void
    {
        // Находим книгу со всеми связанными авторами
        $book = Book::find()->where(['id' => $this->bookId])->with('authors')->one();
        if ($book === null) {
            return; // Книга была удалена до того, как дошла очередь — выходим
        }

        $authorIds = [];
        $authorNames = [];
        foreach ($book->authors as $author) {
            $authorIds[] = $author->id;
            $authorNames[] = $author->full_name;
        }

        if (empty($authorIds)) {
            return;
        }

        $authorsString = implode(', ', $authorNames);

        /** @var MailerInterface $mailer */
        $mailer = Yii::$container->get(MailerInterface::class);

        // Используем метод each(500) для порционной выборки подписчиков из БД.
        $query = AuthorSubscription::find()
            ->select('email')
            ->where(['author_id' => $authorIds])
            ->distinct();

        foreach ($query->each(200) as $subscription) {
            try {
                $mailer->compose()
                    ->setFrom(Yii::$app->params['senderEmail'] ?? 'noreply@example.com')
                    ->setTo($subscription->email)
                    ->setSubject("Новая книга от авторов: {$authorsString}!")
                    ->setTextBody("Добавлена новая книга: \"{$book->title}\" ({$book->release_year} г.)")
                    ->send();
            } catch (\Throwable $e) {
                // Логируем ошибку отправки конкретному пользователю, 
                // чтобы падение одного email не ломало всю рассылку остальным
                Yii::error("Ошибка отправки email на адрес {$subscription->email}: " . $e->getMessage(), 'queue-email');
            }
        }
    }
}
