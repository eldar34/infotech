<?php

declare(strict_types=1);

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

class BookAuthorFixture extends ActiveFixture
{
    public $tableName = '{{%book_author}}';

    public $depends = [
        'tests\unit\fixtures\BookFixture',
        'tests\unit\fixtures\AuthorFixture',
    ];
}
