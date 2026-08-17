<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthorSubscription $model */
?>

<div class="author-subscription-form" style="margin-top: 10px;">
    <?php $form = ActiveForm::begin([
        'action' => ['subscription/subscribe'],
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'author_id')->hiddenInput()->label(false) ?>

    <div class="input-group input-group-sm">
        <?= $form->field($model, 'email', [
            'options' => ['class' => 'form-group mb-0'],
        ])->textInput([
            'placeholder' => 'Ваш Email для новинок',
            'class' => 'form-control form-control-sm',
            'required' => true,
            'readonly' => !Yii::$app->user->isGuest 
        ])->label(false) ?>

        <div class="input-group-append" style="display: inline-block; vertical-align: top;">
            <?= Html::submitButton('Подписаться', ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
