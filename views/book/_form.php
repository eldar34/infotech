<?php

use kartik\select2\Select2;
use kartik\select2\Select2Asset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var @var app\forms\BookForm $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $authorsList Ассоциативный массив авторов [id => full_name] */

Select2Asset::register($this); 
?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Введите название книги']) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'release_year')->textInput([
                'type' => 'number', 
                'min' => 1000, 
                'max' => (int)date('Y'),
                'placeholder' => 'Например: ' . date('Y')
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'isbn')->textInput(['maxlength' => true, 'placeholder' => '13-значный номер без дефисов']) ?>
        </div>
    </div>

    <!-- Множественный выбор авторов -->
    <?= $form->field($model, 'authorIds')->widget(kartik\select2\Select2::class, [
        'data' => $authorsList, 
        'theme' => kartik\select2\Select2::THEME_BOOTSTRAP, 
        'options' => [
            'placeholder' => 'Начните вводить ФИО автора...', 
            'multiple' => true,
            'class' => 'form-control', 
        ],
        'pluginOptions' => [
            'allowClear' => true, 
            'width' => '100%',
            'ajax' => [
                'url' => Url::to(['author/search-list']), 
                'dataType' => 'json',
                'delay' => 250, // Задержка в мс перед отправкой запроса (debounce)
                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new \yii\web\JsExpression('function(author) { return author.text; }'),
            'templateSelection' => new \yii\web\JsExpression('function (author) { return author.text; }'),
        ],
        'pluginLoading' => false,
    ])->label('Авторы книги') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'placeholder' => 'Добавьте краткое описание сюжета...']) ?>

    <!-- Поле для загрузки обложки -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $form->field($model, 'imageFile')->fileInput(['class' => 'form-control-file']) ?>
            
            <!-- Проверяем, передана ли книга и есть ли у неё обложка -->
            <?php if (isset($book) && $book->cover_image): ?>
                <div class="mt-2">
                    <p class="text-muted mb-1">Текущая обложка:</p>
                    <?= Html::img('@web/uploads/covers/' . $book->cover_image, [
                        'class' => 'img-thumbnail', 
                        'style' => 'max-width: 150px;'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <!-- Кнопка отправки формы -->
        <?= Html::submitButton(!isset($book) ? 'Добавить книгу' : 'Сохранить изменения', [
            'class' => !isset($book) ? 'btn btn-success' : 'btn btn-primary'
        ]) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
</div>

    <?php ActiveForm::end(); ?>

</div>
