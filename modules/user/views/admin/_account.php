<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 */

?>

<?php $this->beginContent('@app/modules/user/views/admin/update.php', ['user' => $user]) ?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>

<?= $this->render('_user', ['form' => $form, 'user' => $user]) ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-success']); ?>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
