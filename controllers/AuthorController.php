<?php

declare(strict_types=1);

namespace app\controllers;

use app\forms\AuthorForm;
use app\services\AuthorService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class AuthorController extends Controller
{
    /**
     * Внедряем сервис через конструктор.
     */
    public function __construct(
        $id,
        $module,
        private readonly AuthorService $authorService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete', 'search-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Вывод списка всех авторов
     */
    public function actionIndex()
    {
        $dataProvider = $this->authorService->getProvider(20);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр карточки автора
     */
    public function actionView($id)
    {
        $author = $this->authorService->findModel((int)$id);
        $isUser = !Yii::$app->user->isGuest;

        // Получаем провайдер книг сервиса
        $bookDataProvider = $this->authorService->getBookProvider($author);

        // Cоздание модели подписки через сервис
        $subscriptionModel = $this->authorService->createSubscriptionModel($author->id);

        return $this->render('view', [
            'model' => $author,
            'isUser' => $isUser,
            'bookDataProvider' => $bookDataProvider,
            'subscriptionModel' => $subscriptionModel,
            'books' => $bookDataProvider->getModels(),
            'totalSubs' => (int)$author->getSubscriptions()->count(),
        ]);
    }

    /**
     * Создание автора
     */
    public function actionCreate()
    {
        $form = new AuthorForm();

        if ($this->request->isPost) {
            $model = $this->authorService->create($form, $this->request->post());
            if ($model !== null) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $form, // Передаем форму вместо модели
        ]);
    }

    /**
     * Редактирование автора
     */
    public function actionUpdate($id)
    {
        $model = $this->authorService->findModel((int)$id);
        $form = new AuthorForm();

        // Наполняем форму текущими данными из БД
        $this->authorService->populateForm($model, $form);

        if ($this->request->isPost) {
            if ($this->authorService->update($model, $form, $this->request->post())) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $form, // Передаем форму
        ]);
    }

    /**
     * Удаление автора
     */
    public function actionDelete($id)
    {
        $model = $this->authorService->findModel((int)$id);
        $this->authorService->delete($model);

        return $this->redirect(['index']);
    }

    public function actionSearchList(?string $q = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'results' => $this->authorService->searchAuthors($q)
        ];
    }
}
