<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="author-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'full_name')->textInput([
        'maxlength' => true, 
        'placeholder' => 'Введите ФИО автора'
    ])->label('ФИО Автора') ?>

    <div class="form-group mt-4">
        <?= Html::submitButton(empty($model->id) ? 'Добавить' : 'Сохранить изменения', [
            'class' => empty($model->id) ? 'btn btn-success px-4' : 'btn btn-primary px-4'
        ]) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
