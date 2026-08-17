<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;

// Инициализируем регистрацию ресурсов в Head
$this->render('_head');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?php $this->head() ?>
    <title><?= Html::encode($this->title) ?></title>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Рендерим шапку сайта -->
<?= $this->render('_header') ?>

<!-- Главный контейнер для контента -->
<main id="main" class="flex-shrink-0 pt-3" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget([
                'homeLink' => false,
                'links' => $this->params['breadcrumbs'],
                'options' => ['class' => 'breadcrumb bg-light p-2 rounded-3 small']
            ]) ?>
        <?php endif ?>
        
        <!-- Виджет системных всплывающих сообщений (успех подписки, удаление и т.д.) -->
        <?= Alert::widget() ?>
        
        <!-- Основной контент страницы -->
        <?= $content ?>
    </div>
</main>

<!-- Рендерим подвал сайта -->
<?= $this->render('_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
