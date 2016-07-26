<?php

namespace app\modules\user\models;

use yii\db\ActiveQuery;

/**
 * Class UserQuery
 * @package app\modules\user\models
 */
class UserQuery extends ActiveQuery
{

    /**
     * @param bool $status
     *
     * @return static
     */
    public function active($status = true)
    {
        if ($status) {
            return $this->andWhere(['blocked_at' => null]);
        } else {
            return $this->andWhere(['not', ['blocked_at' => null]]);
        }
    }

}