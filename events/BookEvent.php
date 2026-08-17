<?php

declare(strict_types=1);

namespace app\events;

use yii\base\Event;
use app\models\Book;

class BookEvent extends Event
{    
    /**
     * @var Book
     */
    public Book $book;
}
