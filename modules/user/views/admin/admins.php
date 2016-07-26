<?php

use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var \app\modules\user\models\AdminAddForm $adminAddForm
 * @var array $emails
 */

$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-4',
            'wrapper' => 'col-sm-10',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>
<div class="row">
    <div class="col-xs-4">
        <?= $form->field($adminAddForm, 'email')->widget(Select2::classname(), [
            'data' => $emails,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => 'Выберите пользователя ...',
                'id' => 'user',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-xs-4">
        <div class="form-group">
            <?= Html::submitButton('Добавить', [
                'class' => 'btn btn-success',
            ]); ?>
        </div>
    </div>
</div>
<?php $form::end(); ?>

<div class="row">
    <div class="col-xs-12">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
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
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{delete}',
                    'width' => '50px',
                    'mergeHeader' => false,
                    'buttons' => [
                        'delete' => function ($url, $mdl, $key) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::toRoute([
                                    '/user/admin/delete-admin',
                                    'id' => $mdl->id,
                                ]),
                                [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'data-pjax' => '0',
                                    'data-method' => 'post',
                                    'class' => 'grid-action'
                                ]
                            );
                        }
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>