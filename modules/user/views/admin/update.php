<?php

use app\modules\user\models\User;
use yii\bootstrap\Nav;
use yii\web\View;

/**
 * @var View $this
 * @var $content string
 * @var User $user
 */

$this->title = Yii::t('user', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-xs-10">
        <?= $content; ?>
    </div>
    <div class="col-xs-2">
        <?= Nav::widget([
            'options' => [
                'class' => 'nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => Yii::t('user', 'Account details'), 'url' => ['/user/admin/update', 'id' => $user->id]],
                [
                    'label' => Yii::t('user', 'Profile details'),
                    'url' => ['/user/admin/update-profile', 'id' => $user->id]
                ],
                '<hr/>',
                [
                    'label' => Yii::t('user', 'Delete'),
                    'url' => ['/user/admin/delete', 'id' => $user->id],
                    'linkOptions' => [
                        'class' => 'text-danger',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?'),
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
