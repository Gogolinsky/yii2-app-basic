<?php

namespace app\modules\user\clients;

use yii\authclient\clients\GitHub as BaseGitHub;

/**
 * Class GitHub
 * @package app\modules\user\clients
 */
class GitHub extends BaseGitHub implements ClientInterface
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
        return isset($this->getUserAttributes()['login'])
            ? $this->getUserAttributes()['login']
            : null;
    }
}