<?php

/**
 * @var yii\widgets\ActiveForm
 * @var app\modules\user\models\User
 */
?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>

<?= $form->field($user, 'password')->passwordInput() ?>
