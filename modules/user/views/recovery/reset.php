<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\RecoveryForm $model
 */

$this->title = Yii::t('user', 'Reset your password');

?>

<div class="form-box">

    <h1><?= Html::encode($this->title); ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'password-recovery-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'password')->passwordInput(); ?>

    <?= Html::submitButton(Yii::t('user', 'Finish'), ['class' => 'btn btn-success']); ?><br>

    <?php ActiveForm::end(); ?>

</div>