<?php
namespace app\modules\user\components;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by login.
 */
class LoginAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/user/assets';
    public $css = [
        'css/style.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
