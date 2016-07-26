<?php

namespace app\modules\page;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

/**
 * Class Bootstrap
 * @package app\modules\page
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        /** @var Module $module */
        if ($app->hasModule('page') && ($module = $app->getModule('page')) instanceof Module) {

            $configUrlRule = [
                'rules' => $module->urlRules,
            ];

            $app->get('urlManager')->rules[] = new GroupUrlRule($configUrlRule);

            if (!isset($app->get('i18n')->translations['page'])) {
                $app->get('i18n')->translations['page'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                ];
            }
        }
    }
}