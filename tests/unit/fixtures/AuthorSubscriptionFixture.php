<?php

declare(strict_types=1);

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

class AuthorSubscriptionFixture extends ActiveFixture
{
    public $modelClass = 'app\models\AuthorSubscription';
    // Подписки зависят от существования авторов
    public $depends = [
        'tests\unit\fixtures\AuthorFixture',
    ];
}
