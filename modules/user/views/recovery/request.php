<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\RecoveryForm $model
 */

$this->title = Yii::t('user', 'Recover your password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-box">
    <h1>Восстановление пароля</h1>

    <p>
        Введите email, указанный вами при регистрации на сайте. На него вам придет письмо с ссылкой н восстановление
        пароля.
    </p>

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-password-recovery',
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-primary']) ?><br>

    <?php ActiveForm::end(); ?>
</div>
