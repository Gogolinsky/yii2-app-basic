<?php

namespace app\modules\page;

use app\modules\page\models\Page;
use Yii;

/**
 * Class Module
 * @package app\modules\page
 */
class Module extends \yii\base\Module
{
    /** @var array The rules to be used in URL management. */
    public $urlRules = [];

    public function init()
    {
        parent::init();

        $pages = Page::find()->all();
        foreach ($pages as $page) {
            $this->urlRules["<alias:{$page->alias}>"] = '/page/frontend/view';
        }
    }
}
