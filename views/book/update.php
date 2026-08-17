<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\forms\BookForm $model */
/** @var app\models\Book $book */
/** @var array $authorsList */


$this->title = 'Редактирование книги: ' . $book->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $book->title, 'url' => ['view', 'id' => $book->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card mt-4">
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'book' => $book, 
                'authorsList' => $authorsList,
            ]) ?>
        </div>
    </div>

</div>
