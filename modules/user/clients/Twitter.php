<?php

namespace app\modules\user\clients;

use yii\authclient\clients\Twitter as BaseTwitter;

/**
 * Class Twitter
 */
class Twitter extends BaseTwitter
{
    // current version of twitter api does not provide user's email, so we just
    // have a wrapper for base twitter client
}
