<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\Menu;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->registerLinkTag([
        'href' => 'https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic',
        'rel' => 'stylesheet',
        'type' => 'text/css',
    ]) ?>
    <?= $this->registerLinkTag([
        'href' => '/favicon.png',
        'rel' => 'icon',
        'type' => 'image/png',
    ]) ?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="body">
<?php $this->beginBody() ?>

<header class="header"></header>

<main class="main">
    <?= $content ?>
</main>

<footer class="footer"></footer>
<?php $this->endBody() ?>
<?= Yii::$app->seo->metrika() ?>
<?= Yii::$app->seo->analitics() ?>
</body>
</html>
<?php $this->endPage() ?>
