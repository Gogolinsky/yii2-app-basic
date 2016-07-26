<?php

use yii\helpers\Html;

/**
 * @var \app\modules\page\models\Page[] $models
 */

?>

<?php if (!empty($models)): ?>
    <div class="services">
        <?php foreach ($models as $model): ?>
            <div class="service">
                <?= Html::a(Html::img($model->getImageSrc(), ['class' => 'service-image']), $model->href()) ?>
                <div class="service-body">
                    <?= Html::a($model->getTitle(), $model->href(), ['class' => 'service-link']) ?>
                    <div class="service-text">
                        <?= yii\helpers\HtmlPurifier::process($model->description) ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif; ?>