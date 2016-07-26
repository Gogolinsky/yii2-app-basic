<?php

use yii\bootstrap\Nav;

/**
 * @var yii\web\View $this
 * @var string $content
 */

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
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => Yii::t('page', 'Common'),
                    'url' => ['/page/backend/update', 'id' => $model->id]
                ],
                [
                    'label' => Yii::t('page', 'SEO'),
                    'url' => ['/page/backend/seo', 'id' => $model->id]
                ],
                [
                    'label' => Yii::t('page', 'Delete'),
                    'url' => ['/page/backend/delete', 'id' => $model->id],
                    'linkOptions' => [
                        'class' => 'text-danger',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('page', 'Are you sure you want to delete this page?'),
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>