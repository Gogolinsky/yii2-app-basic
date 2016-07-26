<?php

namespace app\modules\message;

use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

/**
 * Bootstrap class registers module.
 */
class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var Module $module */
        if ($app->hasModule('page') && ($module = $app->getModule('message')) instanceof Module) {

            if (!isset($app->get('i18n')->translations['message'])) {
                $app->get('i18n')->translations['message'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                ];
            }
        }
    }
}
