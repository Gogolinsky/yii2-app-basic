<?php

use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col-xs-3">
        <div class="thumbnail">
            <div class="caption">
                <h3><?= Icon::show('document'); ?> Страницы</h3>
                <p>Редактирование страниц</p>
                <p><?= Html::a('Список', Url::toRoute(['/page/backend/index']), ['class' => 'btn btn-primary']) ?></p>
            </div>
        </div>
    </div>
</div>