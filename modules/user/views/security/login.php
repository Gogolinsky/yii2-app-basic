<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\LoginForm $model
 * @var app\modules\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="form-box">

    <h1>Вход</h1>

    <?php /* Connect::widget([
        'baseAuthUrl' => ['/user/security/auth'],
    ]); */ ?>

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-login',
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
        'validateOnChange' => false,
    ]) ?>

    <?= $form->field($model, 'login',
        ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]) ?>

    <?= $form->field($model, 'password',
        ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label(Yii::t('user',
        'Password')); ?>

    <?php echo $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-primary', 'tabindex' => '3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <hr/>

    <?php if ($module->enablePasswordRecovery): ?>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']); ?>
            </div>
        </div>
    <?php endif ?>

    <?php if ($module->enableConfirmation): ?>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']); ?>
            </div>
        </div>
    <?php endif ?>
    <?php if ($module->enableRegistration): ?>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']); ?>
            </div>
        </div>
    <?php endif ?>
</div>
