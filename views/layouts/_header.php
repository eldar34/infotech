<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

// Формируем список ссылок для меню
$menuItems = [
    [
        'label' => 'Книги', 
        'url' => ['/book/index']
    ],
    [
        'label' => 'Авторы', 
        'url' => ['/author/index']
    ],
    [
        'label' => 'Отчеты', 
        'url' => ['/report/top-authors']
    ],
];

// Если пользователь — гость, показываем кнопку Login
if (Yii::$app->user->isGuest) {
    $menuItems[] = [
        'label' => 'Login', 
        'url' => ['/site/login']
    ];
} else {
    // Если залогинен — показываем имя и кнопку Logout
    $menuItems[] = [
        'label' => 'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post',
            'class' => 'nav-link text-warning fw-bold'
        ]
    ];
}

?>
<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'Books',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top shadow-sm']
    ]);
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-md-0'], // ms-auto прижмет меню к правому краю
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
    
    NavBar::end();
    ?>
</header>
