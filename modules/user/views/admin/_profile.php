<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Profile $profile
 */

?>

<?php $this->beginContent('@app/modules/user/views/admin/update.php', ['user' => $user]) ?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>

<?= $form->field($profile, 'name'); ?>
<?= $form->field($profile, 'last_name'); ?>
<?= $form->field($profile, 'public_email'); ?>
<?= $form->field($profile, 'bio')->textarea(); ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-success']); ?>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
