<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;

$isUser = !Yii::$app->user->isGuest;
?>
<div class="author-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if ($isUser): ?>
            <?= Html::a('Добавить автора', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover mb-0 align-middle'],
                'layout' => "{items}\n<div class='p-3 d-flex justify-content-center'>{pager}</div>",
                'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
                ],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['style' => 'width: 80px;', 'class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center text-muted'],
                    ],
                    [
                        'attribute' => 'full_name',
                        'label' => 'ФИО Автора',
                        'value' => function ($model) {
                            return Html::a(Html::encode($model->full_name), ['view', 'id' => $model->id], ['class' => 'text-decoration-none fw-bold text-dark']);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Кол-во книг',
                        'value' => function ($model) {
                            return count($model->books);
                        },
                        'headerOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'visible' => $isUser, // Кнопки действий видны только залогиненным Юзерам
                        'headerOptions' => ['style' => 'width: 120px;', 'class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('✏️', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-outline-primary', 'title' => 'Редактировать']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('🗑️', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-sm btn-outline-danger',
                                    'title' => 'Удалить',
                                    'data' => [
                                        'confirm' => 'При удалении автора удалятся все его связи с книгами! Продолжить?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
