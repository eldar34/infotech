<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\AuthorSubscription;

class SubscriptionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'subscribe' => ['POST'], // Подписка возможна только через POST
                ],
            ],
        ];
    }

    /**
     * Обработка отправки формы подписки
     */
    public function actionSubscribe()
    {
        $model = new AuthorSubscription();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Вы успешно подписались на уведомления об этом авторе!');
            } else {
                // Извлекаем первую ошибку валидации (например, "Вы уже подписаны")
                $firstError = current($model->getFirstErrors());
                Yii::$app->session->setFlash('error', $firstError ?: 'Не удалось оформить подписку.');
            }
        }

        // Возвращаем пользователя туда, откуда он пришел
        return $this->redirect(Yii::$app->request->referrer ?: ['book/index']);
    }
}
