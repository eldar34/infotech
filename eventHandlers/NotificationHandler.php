<?php

declare(strict_types=1);

namespace app\eventHandlers; 

use app\events\BookEvent;
use app\jobs\SendNotificationJob;
use app\jobs\SendSmsNotificationJob;
use app\models\AuthorSubscription;
use yii\queue\db\Queue;
use yii\web\Session;

class NotificationHandler
{
    public function __construct(
        private readonly Session $session,
        private readonly Queue $queue
    ) {}

    public function notifySubscribers(BookEvent $event): void
    {
        $book = $event->book;
        $authorIds = [];

        foreach ($book->authors as $author) {
            $authorIds[] = $author->id;
        }

        if (empty($authorIds)) {
            return;
        }

        $emails = AuthorSubscription::find()
            ->select('email')
            ->where(['author_id' => $authorIds])
            ->distinct()
            ->limit(5)
            ->column();

        if (empty($emails)) {
            $this->session->setFlash('warning', "[Очередь] На авторов книги \"{$book->title}\" никто не подписан. Задача в очередь не ставилась.");
            return;
        }

        // Создаем задачу для очереди и передаем только ID книги
        $job = new SendNotificationJob([
            'bookId' => (int)$book->id,
        ]);

        $jobId = $this->queue->push($job);

        if (count($emails) > 3) {
            $visibleEmails = array_slice($emails, 0, 3);
            $emailList = implode(', ', $visibleEmails) . '...';
        } else {
            $emailList = implode(', ', $emails);
        }

        // Информируем пользователя через Flash-сообщение
        $this->session->addFlash('success', "[Очередь] Задача на отправку уведомлений добавлена в очередь (ID задачи: {$jobId}). Письма будут отправлены в фоновом режиме на адреса: <em>{$emailList}</em>");

        // Отправка SMS через SMS Pilot 
        // Имитируем, что у нас есть телефоны подписчиков в модели AuthorSubscription
        $phones = ['+79991112233', '+79992223344']; // Тестовые номера

        if (count($phones) > 0) {
            $this->queue->push(new SendSmsNotificationJob([
                'bookId' => (int)$book->id,
            ]));
            $this->session->addFlash('info', "[Очередь] Задача на массовую СМС-рассылку добавлена в очередь.");
        }
    }
}
