<?php

declare(strict_types=1);

namespace app\services;

use yii\db\Query;

class ReportService
{
    /**
     * Получение ТОП-10 авторов за указанный год.
     */
    public function getTopAuthors(int $year): array
    {
        return (new Query())
            ->select([
                'a.id',
                'a.full_name',
                'COUNT(ba.book_id) AS books_count'
            ])
            ->from('{{%author}} a')
            ->innerJoin('{{%book_author}} ba', 'ba.author_id = a.id')
            ->innerJoin('{{%book}} b', 'b.id = ba.book_id')
            ->where(['b.release_year' => $year])
            ->groupBy(['a.id', 'a.full_name'])
            ->orderBy(['books_count' => SORT_DESC])
            ->limit(10)
            ->all();
    }

    /**
     * Получение списка всех уникальных годов выпуска книг для фильтра.
     */
    public function getAvailableYears(int $defaultYear): array
    {
        $availableYears = (new Query())
            ->select(['release_year'])
            ->from('{{%book}}')
            ->distinct()
            ->orderBy(['release_year' => SORT_DESC])
            ->column();

        if (empty($availableYears)) {
            $availableYears = [$defaultYear];
        }

        return array_combine($availableYears, $availableYears);
    }
}
