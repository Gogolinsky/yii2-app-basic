<?php

namespace app\assets;

use yii\web\AssetBundle;

class SlickAsset extends AssetBundle
{
    public $sourcePath = '@bower/slick.js/slick';
    public $css = [
        'slick.css',
        'slick-theme.css',
    ];
    public $js = [
        'slick.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
