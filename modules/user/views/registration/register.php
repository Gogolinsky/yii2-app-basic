<?php

use app\modules\banner\widgets\AsideBannerWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\modules\user\Module $module
 * @var \app\modules\page\models\Page $page
 */

$this->title = $page->meta_t;
$this->registerMetaTag(['name' => 'description', 'content' => $page->meta_d]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->meta_k]);

$this->params['breadcrumbs'][] = Yii::t('user', 'Registration');

?>

<nav class="breadcrumbs-wrap">
    <?= Breadcrumbs::widget([
        'encodeLabels' => false,
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]); ?>
</nav>

<div class="layout-columns">
    <div class="layout-column-main">

        <h1>Регистрация аккаунта</h1>

        <?= $page->content; ?>

        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form-horizontal form-registration',
            ],
        ]); ?>

        <?= $form->field($model, 'name'); ?>

        <?= $form->field($model, 'lastName'); ?>

        <?= $form->field($model, 'phone')->widget(MaskedInput::classname(), [
            'mask' => '+7 (999) 999-9999',
        ]); ?>

        <?= $form->field($model, 'email'); ?>

        <?php if ($module->enableGeneratingPassword == false): ?>
            <?= $form->field($model, 'password')->passwordInput(); ?>
        <?php endif ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-prm']); ?>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="text-center">
            <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']); ?>
        </p>

    </div>
    <div class="layout-column-aside">
        <?= AsideBannerWidget::widget(); ?>
    </div>
</div>
