<?php

namespace app\modules\admin;


use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        /** @var Module $module */
        if ($app->hasModule('admin') && ($module = $app->getModule('admin')) instanceof Module) {

            if (!isset($app->get('i18n')->translations['admin'])) {
                $app->get('i18n')->translations['admin'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                ];
            }
        }
    }
}