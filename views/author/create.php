<?php

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = 'Добавить нового автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">
    <h1 class="mb-4"><?= yii\helpers\Html::encode($this->title) ?></h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>
    </div>
</div>
