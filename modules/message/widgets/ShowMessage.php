<?php

namespace app\modules\message\widgets;

use app\modules\message\models\Message;
use yii\base\Widget;

/**
 * Return Message by alias
 *
 * Class BestRentWidget
 * @property integer $alias
 */
class ShowMessage extends Widget
{
    public $alias = 'default';

    private $_data;

    public function init()
    {
        $this->_data = Message::find()->where(['alias' => $this->alias])->one();
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('message', [
            'data' => $this->_data,
        ]);
    }
}