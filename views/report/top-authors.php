<?php

use yii\helpers\Html;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var array $topAuthors Массив с данными топ-авторов */
/** @var int $selectedYear Выбранный для отчета год */
/** @var array $availableYears Список годов для фильтра */

$this->title = "ТОП-10 авторов за " . $selectedYear . " год";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-top-authors">

    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-muted">Рейтинг авторов, выпустивших наибольшее количество книг за указанный период.</p>

    <!-- Форма выбора года -->
    <div class="card bg-light mb-4">
        <div class="card-body">
            <?= Html::beginForm(['report/top-authors'], 'get', ['class' => 'form-inline d-flex flex-column flex-sm-row align-items-sm-end flex-wrap', 'style' => 'gap: 15px;']) ?>
                
                <!-- Блок селекта -->
                <div class="form-group mb-0 flex-grow-1 flex-sm-grow-0" style="min-width: 250px;">
                    <?= Html::label('Выберите год выпуска:', 'year', ['class' => 'control-label mb-2 font-weight-bold d-block']) ?>
                    
                    <?= Select2::widget([
                        'name' => 'year',
                        'value' => $selectedYear,
                        'data' => $availableYears,
                        'options' => [
                            'id' => 'year-select2',
                            'class' => 'form-control',
                            'placeholder' => 'Выберите год...',
                            'onchange' => 'this.form.submit()' 
                        ],
                        'pluginOptions' => [
                            'allowClear' => false,
                            'hideSearch' => true,
                            'dropdownParent' => 'body', 
                            'dropdownAutoWidth' => true,
                        ],
                    ]); ?>
                </div>
                
                <!-- Блок кнопки -->
                <div class="form-group mb-0">
                    <?= Html::submitButton('Показать', ['class' => 'btn btn-primary px-4']) ?>
                </div>
                
            <?= Html::endForm() ?>
        </div>
    </div>

    <!-- Таблица результатов -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <?php if (!empty($topAuthors)): ?>
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;" class="text-center">Место</th>
                            <th>ФИО Автора</th>
                            <th style="width: 250px;" class="text-center">Кол-во выпущенных книг</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; ?>
                        <?php foreach ($topAuthors as $author): ?>
                            <tr>
                                <td class="text-center font-weight-bold">
                                    <?php if ($rank <= 3): ?>
                                        <!-- Выделяем первые три призовых места эмодзи -->
                                        <span class="badge badge-warning text-dark p-2" style="font-size: 0.9rem;">
                                            <?= $rank == 1 ? '🥇 1' : ($rank == 2 ? '🥈 2' : '🥉 3') ?>
                                        </span>
                                    <?php else: ?>
                                        <?= $rank ?>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle font-weight-bold">
                                    <?= Html::encode($author['full_name']) ?>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-info text-dark p-2" style="font-size: 1rem;">
                                        <?= $author['books_count'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php $rank++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <h5>За <?= $selectedYear ?> год книги не найдены</h5>
                    <p class="mb-0">Попробуйте выбрать другой год в фильтре выше.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
