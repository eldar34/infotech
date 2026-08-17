<?php

declare(strict_types=1);

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\services\ReportService;

class ReportController extends Controller
{
    /**
     * Внедряем ReportService через DI-контейнер.
     */
    public function __construct(
        $id,
        $module,
        private readonly ReportService $reportService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['top-authors'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Вывод ТОП-10 авторов за выбранный год
     */
    public function actionTopAuthors($year = null)
    {
        $year = $year !== null ? (int)$year : (int)date('Y');

        return $this->render('top-authors', [
            'topAuthors' => $this->reportService->getTopAuthors($year),
            'selectedYear' => $year,
            'availableYears' => $this->reportService->getAvailableYears($year),
        ]);
    }
}
