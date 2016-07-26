<?php

namespace app\modules\user\clients;

use yii\authclient\clients\VKontakte as BaseVKontakte;

/**
 * Class VKontakte
 * @package app\modules\user\clients
 */
class VKontakte extends BaseVKontakte implements ClientInterface
{
    /** @inheritdoc */
    public $scope = 'email';

    /** @inheritdoc */
    public function getEmail()
    {
        return $this->getAccessToken()->getParam('email');
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['screen_name'])
            ? $this->getUserAttributes()['screen_name']
            : null;
    }

    /** @inheritdoc */
    protected function defaultTitle()
    {
        return \Yii::t('user', 'VKontakte');
    }
}
