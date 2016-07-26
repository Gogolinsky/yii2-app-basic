<?php

namespace app\modules\user\clients;

use yii\authclient\clients\YandexOAuth as BaseYandex;

/**
 * Class Yandex
 * @package app\modules\user\clients
 */
class Yandex extends BaseYandex implements ClientInterface
{
    /** @inheritdoc */
    public function getEmail()
    {
        $emails = isset($this->getUserAttributes()['emails'])
            ? $this->getUserAttributes()['emails']
            : null;

        if ($emails !== null && isset($emails[0])) {
            return $emails[0];
        } else {
            return;
        }
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['login'])
            ? $this->getUserAttributes()['login']
            : null;
    }

    /** @inheritdoc */
    protected function defaultTitle()
    {
        return \Yii::t('user', 'Yandex');
    }
}
