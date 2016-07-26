<?php

/**
 * @var yii\web\View $this
 * @var \app\modules\message\models\Message $model
 * @var string $content
 */

$this->title = 'Редактирование: ' . $model->title;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('message', 'Messages'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $model->title;

?>

<div class="row">
    <div class="col-xs-12">
        <?= $content; ?>
    </div>
</div>
