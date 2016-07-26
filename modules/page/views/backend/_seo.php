<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Реактирование полей для SEO-оптимизации страницы
 *
 * @var yii\web\View $this
 * @var \app\modules\page\models\Page $model
 */

$this->title = $model->title ? 'Редактирование: ' . $model->title : 'Создание страницы';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('page', 'Pages'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = Yii::t('page', 'Add page');

?>


<?php $this->beginContent('@app/modules/page/views/backend/update.php', compact('model')); ?>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
]); ?>

<?= $form->field($model, 'meta_t')->textInput([
    'class' => 'form-control cadre-light',
    'length' => 70,
])->hint(''); ?>

<?= $form->field($model, 'meta_d')->textarea([
    'rows' => 6,
    'class' => 'form-control cadre-light',
    'length' => 200,
])->hint(''); ?>

<?= $form->field($model, 'meta_k')->textarea(['rows' => 6]); ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']); ?>
</div>

<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
