<?php

use kartik\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\Html;

/**
 * Index layout
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\message\models\MessageSearch $searchModel
 */

$this->title = Yii::t('message', 'Messages');

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
                            Icon::show('plus-outline') . ' ' . Yii::t('message', 'Create message'),
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
                    'attribute' => 'id',
                    'contentOptions' => [
                        'width' => '100px',
                    ],
                ],
                'title',
                'alias',
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate($model->created_at, 'dd.MM.yyyy');
                    },
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'width' => '50px',
                    'mergeHeader' => false,
                ],
            ],
        ]); ?>
    </div>
</div>
