<?php

namespace app\modules\user\clients;

use yii\authclient\clients\Facebook as BaseFacebook;

/**
 * Class Facebook
 * @package app\modules\user\clients
 */
class Facebook extends BaseFacebook implements ClientInterface
{
    /** @inheritdoc */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email'])
            ? $this->getUserAttributes()['email']
            : null;
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return;
    }
}
