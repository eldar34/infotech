<?php

/** @var yii\web\View $this */
/** @var app\forms\BookForm $model */
/** @var array $authorsList */

$this->title = 'Добавить новую книгу';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-create">

    <h1 class="mb-4"><?= yii\helpers\Html::encode($this->title) ?></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'authorsList' => $authorsList,
            ]) ?>
        </div>
    </div>

</div>
