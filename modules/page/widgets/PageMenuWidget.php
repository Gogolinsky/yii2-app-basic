<?php

namespace app\modules\page\widgets;

use app\modules\page\models\Page;
use Yii;
use yii\base\Widget;

/**
 * Class PageMenuWidget
 * @package app\modules\page\widgets
 */
class PageMenuWidget extends Widget
{
    public $id;
    private $_items;

    public function init()
    {
        $model = Page::findOne($this->id);
        $models = $model->children(1)->all();
        $this->_items = $models;
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('menu', [
            'models' => $this->_items,
        ]);
    }
}