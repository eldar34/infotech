<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Проверяем статус пользователя для отображения административных кнопок
$isUser = !Yii::$app->user->isGuest;
?>
<div class="book-view">

    <!-- Кнопки управления для авторизованных пользователей -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="actions-wrapper">
            <?= Html::a('Назад к списку книг', ['index'], ['class' => 'btn btn-outline-secondary mr-2']) ?>
            
            <?php if ($isUser): ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mr-2']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы точно хотите безвозвратно удалить эту книгу?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Левая колонка: Обложка книги -->
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card bg-light shadow-sm text-center p-4 h-100 d-flex align-items-center justify-content-center">
                <?php if ($model->cover_image): ?>
                    <?= Html::img('@web/uploads/covers/' . $model->cover_image, [
                        'class' => 'img-fluid rounded shadow',
                        'style' => 'max-height: 450px; object-fit: contain;',
                        'alt' => $model->title
                    ]) ?>
                <?php else: ?>
                    <div class="text-muted py-5">
                        <span style="font-size: 5rem;" class="d-block mb-3">📚</span>
                        <h5>Обложка отсутствует</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Правая колонка: Информация и Подписки -->
        <div class="col-lg-8 col-md-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    
                    <h3 class="border-bottom pb-2 mb-3 text-secondary">Характеристики</h3>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold text-muted">ISBN номер:</div>
                        <div class="col-sm-9">
                            <span class="badge bg-secondary p-2" style="font-size: 0.95rem; font-family: monospace;">
                                <?= Html::encode($model->isbn) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-sm-3 font-weight-bold text-muted">Год издания:</div>
                        <div class="col-sm-9"><strong><?= Html::encode($model->release_year) ?> г.</strong></div>
                    </div>

                    <!-- Блок авторов с функционалом подписки для Гостей -->
                    <h3 class="border-bottom pb-2 mb-3 text-secondary">Авторы</h3>
                    <div class="authors-list-section mb-4">
                        <?php if (!empty($model->authors)): ?>
                            <div class="row">
                                <?php foreach ($model->authors as $author): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="p-3 border rounded bg-white shadow-sm d-flex flex-column h-100 justify-content-between">
                                            <span class="font-weight-bold text-dark style" style="font-size: 1.1rem;">
                                                👤 <?= Html::a(Html::encode($author->full_name), ['author/view', 'id' => $author->id], ['class' => 'text-dark text-decoration-none']) ?>
                                            </span>
                                            
                                            <!-- Блок подписки показывается ВСЕМ (и гостям, и авторизованным) -->
                                            <div class="mt-2 pt-2 border-top">
                                                <small class="text-muted d-block mb-1">Получать новинки автора:</small>
                                                <?php                                                
                                                    echo $this->render('/subscription/_form', [
                                                        'model' => $author->subscriptionModel
                                                    ]); 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-danger font-italic">Авторы для данной книги не указаны в базе данных.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Описание книги -->
                    <h3 class="border-bottom pb-2 mb-3 text-secondary">Аннотация / Описание</h3>
                    <div class="book-description text-justify" style="line-height: 1.6; white-space: pre-line;">
                        <?= Html::encode($model->description ?: 'Для данной книги описание еще не составлено.') ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
