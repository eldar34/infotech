<?php

use yii\helpers\Html;
use yii\bootstrap5\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
/** @var yii\data\ActiveDataProvider $bookDataProvider */
/** @var array $books */
/** @var bool $isUser */
/** @var int $totalSubs */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1>👤 <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Назад к списку авторов', ['index'], ['class' => 'btn btn-outline-secondary me-2']) ?>
            <?php if ($isUser): ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary me-2']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этого автора?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Левая колонка: Книги автора -->
        <div class="col-md-8">
            <h3 class="mb-3 text-secondary">Книги этого автора</h3>
            <?php if (!empty($books)): ?>
                <div class="list-group shadow-sm mb-3">
                    <?php foreach ($books as $book): ?>
                        <?= Html::a(
                            '📖 ' . Html::encode($book->title) . ' (' . Html::encode($book->release_year) . ' г.)',
                            ['book/view', 'id' => $book->id],
                            ['class' => 'list-group-item list-group-item-action']
                        ) ?>
                    <?php endforeach; ?>
                </div>

                <!-- Пагинация для Книг -->
                <div class="d-flex justify-content-center">
                    <?= LinkPager::widget([
                        'pagination' => $bookDataProvider->getPagination(),
                        'options' => ['class' => 'pagination pagination-sm'],
                        'linkContainerOptions' => ['class' => 'page-item'],
                        'linkOptions' => ['class' => 'page-link'],
                    ]) ?>
                </div>
            <?php else: ?>
                <div class="alert alert-light border text-muted">
                    У этого автора пока нет выпущенных книг.
                </div>
            <?php endif; ?>
        </div>

        <!-- Правая колонка: Единый блок подписки (виден ВСЕМ) -->
        <div class="col-md-4">
            <div class="card shadow-sm border-primary mb-4">
                <div class="card-body">
                    <!-- Заголовок со встроенным счетчиком общего числа подписчиков -->
                    <h5 class="card-title text-primary fw-bold d-flex justify-content-between align-items-center">
                        <span>🔔 Подписка на новинки</span>
                        <span class="badge bg-primary rounded-pill small" title="Всего подписчиков у автора">
                            👥 <?= $totalSubs ?>
                        </span>
                    </h5>
                    
                    <p class="card-text text-muted small mt-2">Оставьте свой Email, чтобы первыми узнавать о выходе новых книг этого автора.</p>
                    
                    <?php 
                    // Если пользователь авторизован, подставляем его email по умолчанию
                    $defaultEmail = $isUser ? Yii::$app->user->identity->email : '';
                    echo $this->render('/subscription/_form', [
                        'model' => $subscriptionModel
                    ]); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
