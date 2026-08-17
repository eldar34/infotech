<?php

declare(strict_types=1);

namespace app\controllers;

use app\forms\BookForm;
use app\services\BookService;
use app\services\AuthorService;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BookController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly BookService $bookService,
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
                        'actions' => ['create', 'update', 'delete'],
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
     * Вывод списка книг
     */
    public function actionIndex()
    {
        $dataProvider = $this->bookService->getProvider(12);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр карточки книги
     */
    public function actionView($id)
    {
        $model = $this->bookService->findModel((int)$id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Создание новой книги
     */
    public function actionCreate()
    {
        $form = new BookForm();

        if ($this->request->isPost && $form->load($this->request->post())) {
            $form->imageFile = UploadedFile::getInstance($form, 'imageFile');

            if ($form->validate()) {
                $book = $this->bookService->create($form);

                if ($book !== null) {
                    return $this->redirect(['view', 'id' => $book->id]);
                }
            }
        }

        $authorsList = $this->authorService->getListByIds($form->authorIds);

        return $this->render('create', [
            'model' => $form,
            'authorsList' => $authorsList,
        ]);
    }

    /**
     * Редактирование существующей книги
     */
    public function actionUpdate($id)
    {
        $book = $this->bookService->findModel((int)$id);
        $form = new BookForm();

        // Заполняем форму текущими данными из БД через сервис
        $this->bookService->populateForm($book, $form);

        if ($this->request->isPost && $form->load($this->request->post())) {
            $form->imageFile = UploadedFile::getInstance($form, 'imageFile');

            if ($form->validate()) {
                if ($this->bookService->update($book, $form)) {
                    return $this->redirect(['view', 'id' => $book->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $form,
            'book' => $book,
            'authorsList' => \yii\helpers\ArrayHelper::map($book->authors, 'id', 'full_name')
        ]);
    }

    /**
     * Удаление книги
     */
    public function actionDelete($id)
    {
        $book = $this->bookService->findModel((int)$id);

        $this->bookService->delete($book);

        return $this->redirect(['index']);
    }
}
