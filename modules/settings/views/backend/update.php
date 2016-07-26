<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \app\modules\settings\models\Settings $model
 */

$this->title = 'Настройки сайта';
$this->params['breadcrumbs'][] = Yii::t('settings', 'Settings');

?>

<div class="row">
    <div class="col-xs-12">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email_callback') ?>
        <?= $form->field($model, 'email_noreply') ?>
        <?= $form->field($model, 'yandex_webmaster') ?>
        <?= $form->field($model, 'google_webmaster') ?>
        <?= $form->field($model, 'metrika_code')->textarea(['rows' => 8]) ?>
        <?= $form->field($model, 'analitics_code')->textarea(['rows' => 8]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
