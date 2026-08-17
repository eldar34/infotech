<?php

use yii\helpers\Html;
use yii\bootstrap5\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;

// Проверяем, авторизован ли текущий пользователь
$isUser = !Yii::$app->user->isGuest;
?>
<div class="book-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <?php if ($isUser): ?>
            <!-- Кнопка добавления доступна только авторизованным пользователям -->
            <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <div class="row">
        <?php if ($dataProvider->getCount() > 0): ?>
            <?php foreach ($dataProvider->getModels() as $book): ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        
                        <!-- Вывод обложки книги -->
                        <div class="text-center bg-light p-3" style="height: 250px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <?php if ($book->cover_image): ?>
                                <?= Html::img('@web/uploads/covers/' . $book->cover_image, [
                                    'class' => 'img-fluid',
                                    'style' => 'max-height: 100%; object-fit: contain;',
                                    'alt' => $book->title
                                ]) ?>
                            <?php else: ?>
                                <!-- Заглушка, если фото нет -->
                                <div class="text-muted d-flex flex-column align-items-center">
                                    <span style="font-size: 3rem;">📚</span>
                                    <small>Нет обложки</small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Тело карточки -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate mb-1" title="<?= Html::encode($book->title) ?>">
                                <?= Html::a(Html::encode($book->title), ['view', 'id' => $book->id], ['class' => 'text-dark font-weight-bold']) ?>
                            </h5>
                            
                            <p class="text-muted small mb-2">Год выпуска: <strong><?= Html::encode($book->release_year) ?></strong></p>
                            
                            <!-- Список авторов книги -->
                            <div class="mb-3 flex-grow-1">
                                <small class="text-muted d-block">Авторы:</small>
                                <?php if (!empty($book->authors)): ?>
                                    <?php 
                                    $authorNames = array_map(function($author) {
                                        return Html::encode($author->full_name);
                                    }, $book->authors);
                                    echo implode(', ', $authorNames);
                                    ?>
                                <?php else: ?>
                                    <span class="text-danger small">Автор не указан</span>
                                <?php endif; ?>
                            </div>

                            <p class="card-text text-muted small text-justify text-truncate-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 4.5em; line-height: 1.5em;">
                                <?= Html::encode($book->description ?: 'Описание отсутствует.') ?>
                            </p>

                            <!-- Кнопки действий -->
                            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                <?= Html::a('Подробнее', ['view', 'id' => $book->id], ['class' => 'btn btn-outline-primary btn-sm btn-block' . ($isUser ? ' mr-2' : '')]) ?>
                                
                                <?php if ($isUser): ?>
                                    <div class="btn-group ml-auto">
                                        <?= Html::a('✏️', ['update', 'id' => $book->id], ['class' => 'btn btn-sm btn-outline-secondary', 'title' => 'Редактировать']) ?>
                                        <?= Html::a('🗑️', ['delete', 'id' => $book->id], [
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'title' => 'Удалить',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <h3>Список книг пока пуст</h3>
                    <p class="text-muted">В базе данных ещё нет зарегистрированных книг.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Пагинация -->
    <div class="d-flex justify-content-center mt-4">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'pagination'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link']
        ]) ?>
    </div>

</div>
