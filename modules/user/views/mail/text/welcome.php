<?php

/**
 * @var app\modules\user\models\User
 */
?>
<?= Yii::t('user', 'Hello'); ?>,

<?= Yii::t('user', 'Your account on {0} has been created', Yii::$app->name); ?>.
<?= Yii::t('user', 'You can now log in with the following credentials:'); ?>.

<?= Yii::t('user', 'Email') ?>: <?= $user->email; ?>

<?= Yii::t('user', 'Password') ?>: <?= $user->password; ?>

<?= Yii::t('user', 'If you did not make this request you can ignore this email'); ?>.
