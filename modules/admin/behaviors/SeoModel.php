<?php

namespace app\modules\admin\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\Html;

/**
 * Class SeoModel
 * @package app\modules\admin\behaviors
 */
class SeoModel extends Behavior
{
    /**
     * @param $tag
     * @return string
     */
    public function getMetaTag($tag)
    {
        switch ($tag) {
            case 'meta_t':
                return $this->owner->meta_t
                    ? Html::encode($this->owner->meta_t)
                    : Html::encode($this->owner->title);
            case 'meta_d':
                return $this->owner->meta_d
                    ? Html::encode($this->owner->meta_d)
                    : Html::encode($this->owner->title);
            case 'meta_k':
                return $this->owner->meta_k
                    ? Html::encode($this->owner->meta_k)
                    : Html::encode($this->owner->title);
            default:
                return Html::encode($this->owner->title);
        }
    }
}