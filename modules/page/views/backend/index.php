<?php

use app\modules\page\models\PageSearch;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\page\models\PageSearch $searchModel
 */

$this->title = Yii::t('page', 'Pages');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-xs-12">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summaryOptions' => ['class' => 'text-right'],
            'bordered' => false,
            'pjax' => true,
            'pjaxSettings' => [
                'options' => [
                    'id' => 'grid',
                ],
            ],
            'striped' => false,
            'hover' => true,
            'panel' => [
                'type' => GridView::TYPE_ACTIVE,
                'before',
                'after' => false,
            ],
            'toolbar' => [
                [
                    'content' =>
                        Html::a(
                            Icon::show('plus-outline') . ' ' . Yii::t('page', 'Add page'),
                            ['create'],
                            [
                                'data-pjax' => 0,
                                'class' => 'btn btn-success'
                            ]
                        ) . ' ' .
                        Html::a(
                            Icon::show('arrow-sync-outline'),
                            ['index'],
                            [
                                'data-pjax' => 0,
                                'class' => 'btn btn-default',
                                'title' => Yii::t('app', 'Reset')
                            ]
                        )
                ],
                '{toggleData}',
                '{export}',
            ],
            'toggleDataOptions' => [
                'all' => [
                    'icon' => 'resize-full',
                    'label' => 'Показать все',
                    'class' => 'btn btn-default',
                    'title' => 'Показать все'
                ],
                'page' => [
                    'icon' => 'resize-small',
                    'label' => 'Страницы',
                    'class' => 'btn btn-default',
                    'title' => 'Постаничная разбивка'
                ],
            ],
            'export' => [
                'target' => GridView::TARGET_BLANK,
                'label' => 'Экспорт',
                'header' => '<li role="presentation" class="dropdown-header">Экспорт данных</li>',
            ],
            'exportConfig' => [
                GridView::EXCEL => [
                    'label' => 'Excel',
                    'icon' => 'floppy-remove',
                    'iconOptions' => ['class' => 'text-success'],
                    'showHeader' => true,
                    'showPageSummary' => true,
                    'showFooter' => true,
                    'showCaption' => true,
                    'filename' => 'client_export_excel',
                    'mime' => 'application/vnd.ms-excel',
                    'config' => [
                        'worksheet' => 'ExportWorksheet',
                        'cssFile' => ''
                    ]
                ],
                GridView::CSV => [
                    'label' => 'CSV',
                    'icon' => 'floppy-open',
                    'iconOptions' => ['class' => 'text-primary'],
                    'showHeader' => true,
                    'showPageSummary' => true,
                    'showFooter' => true,
                    'showCaption' => true,
                    'filename' => 'client_export_cvs',
                    'mime' => 'application/csv',
                    'config' => [
                        'colDelimiter' => ",",
                        'rowDelimiter' => "\r\n",
                    ]
                ],
            ],
            'columns' => [
                [
                    'class' => DataColumn::className(),
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'attribute' => 'id',
                    'contentOptions' => [
                        'width' => '100px',
                    ],
                ],
                [
                    'class' => DataColumn::className(),
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => 'raw',
                    'value' => function ($model) {
                        /** @var \app\modules\page\models\Page $model */
                        if ($model->image) {
                            return Html::img($model->getPathToImages($model->image), ['width' => '100px']);
                        } else {
                            return false;
                        }
                    },
                    'contentOptions' => [
                        'width' => '100px',
                    ],
                ],
                [
                    'class' => DataColumn::className(),
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'attribute' => 'title',
                ],
                [
                    'class' => DataColumn::className(),
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'attribute' => 'alias',
                ],
                [
                    'class' => DataColumn::className(),
                    'attribute' => 'parent',
                    'label' => 'Родительская страница',
                    'filter' => PageSearch::getDropDown(),
                    'value' => function ($model) {
                        if (null !== $model->parents(1)->one()) {
                            return $model->parents(1)->one()->title;
                        } else {
                            return 'Это корень';
                        }
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update}',
                    'width' => '50px',
                    'mergeHeader' => false,
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a(
                                'Редактировать',
                                $url,
                                [
                                    'title' => Yii::t('yii', 'Update'),
                                    'data-pjax' => '0',
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
