<?php

use kartik\icons\Icon;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

$asset = app\modules\admin\components\AdminAsset::register($this);

?>

<?php $this->beginPage(); ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags(); ?>
        <link rel="icon" type="image/png" href="/favicon.png" sizes="32x32"/>
        <title><?= Html::encode($this->title); ?></title>
        <?php $this->head(); ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>
    <div class="page-wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Url::toRoute('/'),
            'options' => ['class' => 'navbar-inverse'],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav'],
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => Icon::show('home') . Yii::t('admin', 'Home'),
                    'url' => ['/admin/default/index']
                ],
                [
                    'label' => Icon::show('pencil') . Yii::t('admin', 'Content'),
                    'items' => [
                        [
                            'label' => Icon::show('document') . Yii::t('page', 'Pages'),
                            'url' => Url::toRoute('/page/backend/index')
                        ],
                    ],
                ],
                [
                    'label' => Icon::show('cog-outline') . Yii::t('settings', 'Settings'),
                    'items' => [
                        [
                            'label' => Icon::show('message') . Yii::t('message', 'Messages'),
                            'url' => ['/message/backend/index'],
                        ],
                        [
                            'label' => Icon::show('cog') . Yii::t('settings', 'Settings'),
                            'url' => ['/settings/backend/update'],
                        ],
                        [
                            'label' => Icon::show('user-outline') . Yii::t('user', 'Users'),
                            'url' => ['/user/admin/index'],
                        ],
                    ],
                ],
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => Yii::$app->user->identity->email,
                    'items' => [
                        [
                            'label' => Icon::show('power') . ' ' . Yii::t('admin', 'Logout'),
                            'url' => Url::toRoute('/user/security/logout'),
                            'linkOptions' => [
                                'data-method' => 'post',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container">

            <?= yii\widgets\Breadcrumbs::widget([
                'homeLink' => [
                    'label' => Yii::t('admin', 'Home'),
                    'url' => Url::toRoute('/admin/default/index'),
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>

            <div class="row">
                <div class="col-xs-12">
                    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                        <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                            <div class="alert alert-<?= $type ?>" role="alert">
                                <?= $message ?>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>


            <?= $content ?>

        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">Разработано в <a href="http://dancecolor.ru">Dancecolor</a></p>
        </div>
    </footer>
    <?php $this->endBody() ?>
    </body>
    </html>

<?php $this->endPage() ?>