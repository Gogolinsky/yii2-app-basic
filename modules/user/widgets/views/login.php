<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $form yii\widgets\ActiveForm
 * @var $model app\modules\user\models\LoginForm
 * @var $action string
 */
?>

<?php if (Yii::$app->user->isGuest): ?>

    <p class="text-center">
        <?= Html::a(Yii::t('user', 'Sign up'), ['/user/registration/register']); ?>
    </p>

    <p class="text-center">
        <?= Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request']); ?>
    </p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-widget-form',
        'fieldConfig' => [
            'template' => "{input}\n{error}",
        ],
        'action' => $action,
    ]); ?>

    <?= $form->field($model, 'login')->textInput(['placeholder' => 'E-mail']); ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль']); ?>

    <?= $form->field($model, 'rememberMe')->checkbox(); ?>

    <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-primary btn-block']); ?>

    <?php ActiveForm::end(); ?>

<?php else: ?>

    <?= Html::a(Yii::t('user', 'Logout'), ['/user/security/logout'],
        ['class' => 'btn btn-danger btn-block', 'data-method' => 'post']); ?>

<?php endif ?>
