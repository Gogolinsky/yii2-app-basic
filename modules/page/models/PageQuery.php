<?php

namespace app\modules\page\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * Class PageQuery
 * @package app\modules\page\models
 */
class PageQuery extends ActiveQuery
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}