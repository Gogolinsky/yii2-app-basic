<?php

namespace app\modules\admin\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class BlockModel
 * @package app\modules\admin\behaviors
 */
class BlockModel extends Behavior
{
    /**
     * @return bool Whether the model is blocked or not.
     */
    public function getIsActive()
    {
        return $this->owner->blocked_at == null;
    }

    /**
     * @return bool Whether the model is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->owner->blocked_at != null;
    }

    /**
     * Blocks the model by setting 'blocked_at' field to current time.
     */
    public function block()
    {
        return (bool)$this->owner->updateAttributes(['blocked_at' => time()]);
    }

    /**
     * UnBlocks the model by setting 'blocked_at' field to null.
     */
    public function unblock()
    {
        return (bool)$this->owner->updateAttributes(['blocked_at' => null]);
    }
}