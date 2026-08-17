<?php

declare(strict_types=1);

namespace app\bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use app\models\Book;
use app\events\BookEvent;
use app\eventHandlers\NotificationHandler;

class EventBootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        // При создании книги вызовется этот обработчик.
        Event::on(
            Book::class, 
            Book::EVENT_BOOK_CREATED, 
            static function (BookEvent $event) {
                // Извлекаем обработчик из DI-контейнера в момент наступления события
                /** @var NotificationHandler $handler */
                $handler = Yii::$container->get(NotificationHandler::class);
                
                $handler->notifySubscribers($event);
            }
        );
    }
}
