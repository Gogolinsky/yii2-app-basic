<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\log\Logger;

/**
 * Class AdminAddForm
 * @package app\modules\user\models
 */
class AdminAddForm extends Model
{
    /** @var string $email */
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                'email',
                function ($attribute) {
                    if (!User::findOne(['email' => $this->email])) {
                        $this->addError($attribute, Yii::t('user', 'User with such email does not exist'));
                    }
                }
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'Email'),
        ];
    }

    /**
     * Add user to admins
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function add()
    {
        /** @var User $user */
        $user = User::findOne(['email' => $this->email]);

        if (!is_null($user)) {
            if (!Yii::$app->authManager->checkAccess($user->id, 'administrator')) {
                if (Yii::$app->authManager->checkAccess($user->id, 'user')) {
                    $role = Yii::$app->authManager->getRole('user');
                    Yii::$app->authManager->revoke($role, $user->id);
                }
                $userRole = Yii::$app->authManager->getRole('administrator');
                Yii::$app->authManager->assign($userRole, $user->id);
            }

            Yii::getLogger()->log('Assign administrator role to' . $this->email, Logger::LEVEL_ERROR);

            return true;
        }

        return false;
    }
}
