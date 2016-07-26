<?php

use yii\widgets\Breadcrumbs;

/**
 * @var yii\web\view $this
 * @var string $content
 */

?>

<?php $this->beginContent('@app/views/layouts/main.php') ?>

<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>

<?= $content ?>

<?php $this->endContent() ?>