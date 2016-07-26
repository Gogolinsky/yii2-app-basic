<?php

use app\modules\user\models\UserSearch;
use kartik\grid\DataColumn;
use kartik\icons\Icon;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage users');
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
                            Icon::show('plus-outline') . ' Создать пользователя',
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
                'email',
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate($model->created_at, 'dd.MM.yyyy');
                    },
                ],
                [
                    'class' => DataColumn::className(),
                    'label' => 'Статус',
                    'content' => function ($model, $key, $index, $column) {
                        /** @var \app\modules\user\models\User $model */
                        return Html::checkbox(
                            'bock',
                            $model->getIsActive(),
                            [
                                'class' => 'switch',
                                'data-id' => $model->id,
                                'data-link' => Url::toRoute(['/user/admin/switch-block']),
                            ]);
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                ],
            ],
        ]); ?>
    </div>
</div>