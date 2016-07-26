<?php

use app\modules\page\widgets\PageMenuWidget;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \app\modules\page\models\Page $model
 * @var \app\modules\page\models\Page[] $parents
 */

$this->title = $model->getMetaTag('meta_t');
$this->registerMetaTag(['name' => 'description', 'content' => $model->getMetaTag('meta_d')]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->getMetaTag('meta_k')]);

foreach ($parents as $parent) {
    $this->params['breadcrumbs'][] = [
        'label' => $parent->title,
        'url' => ['/page/frontend/view', 'alias' => $parent->alias]
    ];
}
$this->params['breadcrumbs'][] = Html::encode($model->title);

?>

<?= $model->content ?>
<?= PageMenuWidget::widget(['id' => $model->id]) ?>
