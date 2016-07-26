<?php

use app\modules\user\models\User;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var User $user
 */

$this->title = Yii::t('user', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="row">
    <div class="col-xs-10">
        <p><?= Yii::t('user', 'Credentials will be sent to the user by email'); ?>.</p>
        <p><?= Yii::t('user', 'A password will be generated automatically if not provided'); ?>.</p>

        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_user', ['form' => $form, 'user' => $user]); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-success']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-xs-2">

        <?= Nav::widget([
            'options' => [
                'class' => 'nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => Yii::t('user', 'Account details'), 'url' => ['/user/admin/create']],
                [
                    'label' => Yii::t('user', 'Profile details'),
                    'options' => [
                        'class' => 'disabled',
                        'onclick' => 'return false;',
                    ]
                ],
                [
                    'label' => Yii::t('user', 'Information'),
                    'options' => [
                        'class' => 'disabled',
                        'onclick' => 'return false;',
                    ]
                ],
            ],
        ]) ?>

    </div>
</div>