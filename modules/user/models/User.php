<?php

namespace app\modules\user\models;

use app\modules\admin\behaviors\BlockModel;
use app\modules\user\helpers\Password;
use app\modules\user\Mailer;
use app\modules\user\Module;
use RuntimeException;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\log\Logger;
use yii\web\Application;
use yii\web\IdentityInterface;

/**
 * User ActiveRecord model.
 *
 * @property bool $isAdmin
 * @property bool $isConfirmed
 * @property bool $isMallOwner
 * @property bool $isShopOwner
 *
 * Database fields:
 * @property integer $id
 * @property string $email
 * @property string $unconfirmed_email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $registration_ip
 * @property integer $confirmed_at
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 *
 * Defined relations:
 * @property Account[] $accounts
 * @property Profile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const USER_CREATE_INIT = 'user_create_init';
    const USER_CREATE_DONE = 'user_create_done';
    const USER_REGISTER_INIT = 'user_register_init';
    const USER_REGISTER_DONE = 'user_register_done';

    // following constants are used on secured email changing process
    const OLD_EMAIL_CONFIRMED = 0b1;
    const NEW_EMAIL_CONFIRMED = 0b10;

    /** @var string User name. Used for user create. */
    public $name;

    /** @var string User last name. Used for user create. */
    public $lastName;

    /** @var string Plain password. Used for model validation. */
    public $password;

    /** @var \app\modules\user\Module */
    protected $module;

    /** @var \app\modules\user\Mailer */
    protected $mailer;

    /** @inheritdoc */
    public function init()
    {
        $this->mailer = \Yii::$container->get(Mailer::className());
        $this->module = \Yii::$app->getModule('user');
        parent::init();
    }


    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->confirmed_at != null;
    }

    /**
     * @return bool Whether the user is an admin or not.
     */
    public function getIsAdmin()
    {
        return in_array($this->email, $this->module->admins);
    }

    /**
     * @return bool Whether the user is an mall owner.
     */
    public function getIsMallOwner()
    {
        return (bool)self::getMallUsers()->where(['user_id' => $this->id])->all();
    }

    /**
     * @return bool Whether the user is an mall owner.
     */
    public function getIsShopOwner()
    {
        return (bool)self::getShopUsers()->where(['user_id' => $this->id])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne($this->module->modelMap['Profile'], ['user_id' => 'id']);
    }

    /**
     * @return Account[] Connected accounts ($provider => $account)
     */
    public function getAccounts()
    {
        $connected = [];
        $accounts = $this->hasMany($this->module->modelMap['Account'], ['user_id' => 'id'])->all();

        /** @var Account $account */
        foreach ($accounts as $account) {
            $connected[$account->provider] = $account;
        }

        return $connected;
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /** @inheritdoc */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
            'unconfirmed_email' => Yii::t('user', 'New email'),
            'password' => Yii::t('user', 'Password'),
            'created_at' => Yii::t('user', 'Registration time'),
            'confirmed_at' => Yii::t('user', 'Confirmation time'),
            'name' => Yii::t('user', 'Name'),
            'lastName' => Yii::t('user', 'Last name'),
        ];
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlockModel::className(),
            ]
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'register' => ['email', 'password', 'name', 'lastName'],
            'connect' => ['username', 'email'],
            'create' => ['name', 'lastName', 'email', 'password'],
            'update' => ['username', 'email', 'password'],
            'settings' => ['username', 'email', 'password'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 3, 'max' => 50],
            ['name', 'trim'],
            ['lastName', 'filter', 'filter' => 'trim'],
            ['lastName', 'string', 'min' => 3, 'max' => 50],
            ['lastName', 'trim'],
            ['username', 'required', 'on' => ['connect', 'create', 'update']],
            ['username', 'string', 'min' => 3, 'max' => 50],
            ['username', 'trim'],
            ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],
            ['email', 'trim'],
            ['password', 'required', 'on' => ['register']],
            ['password', 'string', 'min' => 6, 'on' => ['register', 'create']],
            ['password', 'trim'],
        ];
    }

    /** @inheritdoc */
    public function validateAuthKey($authKey)
    {
        return $this->getAttribute('auth_key') == $authKey;
    }

    /**
     * This method is used to create new user account. If password is not set, this method will generate new 8-char
     * password. After saving user to database, this method uses mailer component to send credentials
     * (username and password) to user via email.
     *
     * @return bool
     */
    public function create()
    {
        if ($this->getIsNewRecord() == false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $this->confirmed_at = time();

        if ($this->password == null) {
            $this->password = Password::generate(8);
        }

        $this->trigger(self::USER_CREATE_INIT);

        if ($this->save()) {
            $this->trigger(self::USER_CREATE_DONE);

            //Привязываем пользователю роль User
            $role = Yii::$app->authManager->getRole('administrator');
            Yii::$app->authManager->assign($role, $this->id);

            $this->mailer->sendWelcomeMessage($this);
            Yii::getLogger()->log('User has been created', Logger::LEVEL_INFO);

            return true;
        }

        Yii::getLogger()->log('An error occurred while creating user account', Logger::LEVEL_ERROR);

        return false;
    }

    /**
     * This method is used to register new user account. If Module::enableConfirmation is set true, this method
     * will generate new confirmation token and use mailer to send it to the user. Otherwise it will log the user in.
     * If Module::enableGeneratingPassword is set true, this method will generate new 8-char password. After saving user
     * to database, this method uses mailer component to send credentials (username and password) to user via email.
     *
     * @return bool
     */
    public function register()
    {
        if ($this->getIsNewRecord() == false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if ($this->module->enableConfirmation == false) {
            $this->confirmed_at = time();
        }

        if ($this->module->enableGeneratingPassword) {
            $this->password = Password::generate(8);
        }

        $this->trigger(self::USER_REGISTER_INIT);

        if ($this->save()) {
            $this->trigger(self::USER_REGISTER_DONE);

            //Привязываем пользователю роль User
            $role = Yii::$app->authManager->getRole('user');
            Yii::$app->authManager->assign($role, $this->id);

            if ($this->module->enableConfirmation) {
                $token = Yii::createObject([
                    'class' => Token::className(),
                    'type' => Token::TYPE_CONFIRMATION,
                ]);
                $token->link('user', $this);
                $this->mailer->sendConfirmationMessage($this, $token);
            } else {
                Yii::$app->user->login($this);
            }

            if ($this->module->enableGeneratingPassword) {
                $this->mailer->sendWelcomeMessage($this);
            }
            Yii::$app->session->setFlash('info', $this->getFlashMessage());
            Yii::getLogger()->log('User has been registered', Logger::LEVEL_INFO);

            return true;
        }

        Yii::getLogger()->log('An error occurred while registering user account', Logger::LEVEL_ERROR);

        return false;
    }

    /**
     * This method attempts user confirmation. It find token with given code and if it is expired
     * or does not exist, this method will throw exception.
     *
     * If confirmation passes it will return true, otherwise it will return false.
     *
     * @param string $code Confirmation code.
     */
    public function attemptConfirmation($code)
    {
        /** @var Token $token */
        $token = Token::find()->where([
            'user_id' => $this->id,
            'code' => $code,
            'type' => Token::TYPE_CONFIRMATION,
        ])->one();

        if ($token === null || $token->isExpired) {
            Yii::$app->session->setFlash('danger',
                Yii::t('user', 'The confirmation link is invalid or expired. Please try requesting a new one.'));
        } else {
            $token->delete();

            $this->confirmed_at = time();

            Yii::$app->user->login($this);

            Yii::getLogger()->log('User has been confirmed', Logger::LEVEL_INFO);

            if ($this->save(false)) {
                Yii::$app->session->setFlash('success', \Yii::t('user', 'Thank you, registration is now complete.'));
            } else {
                Yii::$app->session->setFlash('danger',
                    Yii::t('user', 'Something went wrong and your account has not been confirmed.'));
            }
        }
    }

    /**
     * This method attempts changing user email. If user's "unconfirmed_email" field is empty is returns false, else if
     * somebody already has email that equals user's "unconfirmed_email" it returns false, otherwise returns true and
     * updates user's password.
     *
     * @param string $code
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function attemptEmailChange($code)
    {
        /** @var Token $token */
        $token = Token::find()->where([
            'user_id' => $this->id,
            'code' => $code,
        ])->andWhere(['in', 'type', [Token::TYPE_CONFIRM_NEW_EMAIL, Token::TYPE_CONFIRM_OLD_EMAIL]])->one();

        if (empty($this->unconfirmed_email) || $token === null || $token->isExpired) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Your confirmation token is invalid or expired'));
        } else {
            $token->delete();

            if (empty($this->unconfirmed_email)) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'An error occurred processing your request'));
            } elseif (static::find()->where(['email' => $this->unconfirmed_email])->exists() == false) {
                if ($this->module->emailChangeStrategy == Module::STRATEGY_SECURE) {
                    switch ($token->type) {
                        case Token::TYPE_CONFIRM_NEW_EMAIL:
                            $this->flags |= self::NEW_EMAIL_CONFIRMED;
                            Yii::$app->session->setFlash('success', Yii::t('user',
                                'Awesome, almost there. Now you need to click the confirmation link sent to your old email address'));
                            break;
                        case Token::TYPE_CONFIRM_OLD_EMAIL:
                            $this->flags |= self::OLD_EMAIL_CONFIRMED;
                            Yii::$app->session->setFlash('success', Yii::t('user',
                                'Awesome, almost there. Now you need to click the confirmation link sent to your new email address'));
                            break;
                    }
                }
                if ($this->module->emailChangeStrategy == Module::STRATEGY_DEFAULT || ($this->flags & self::NEW_EMAIL_CONFIRMED && $this->flags & self::OLD_EMAIL_CONFIRMED)) {
                    $this->email = $this->unconfirmed_email;
                    $this->unconfirmed_email = null;
                    Yii::$app->session->setFlash('success', Yii::t('user', 'Your email address has been changed'));
                }
                $this->save(false);
            }
        }
    }

    /**
     * Resets password.
     *
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($password)
    {
        return (bool)$this->updateAttributes(['password_hash' => Password::hash($password)]);
    }

    /**
     * Confirms the user by setting 'confirmed_at' field to current time.
     */
    public function confirm()
    {
        return (bool)$this->updateAttributes(['confirmed_at' => time()]);
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
            if (Yii::$app instanceof Application) {
                $this->setAttribute('registration_ip', Yii::$app->request->userIP);
            }
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $profile = Yii::createObject([
                'class' => Profile::className(),
                'user_id' => $this->id,
                'gravatar_email' => $this->email,
                'name' => $this->name,
                'last_name' => $this->lastName,
            ]);
            $profile->save(false);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return string
     */
    protected function getFlashMessage()
    {
        if ($this->module->enableGeneratingPassword && $this->module->enableConfirmation) {
            return Yii::t('user',
                'A message has been sent to your email address. It contains your password and a confirmation link that you must click to complete registration.');
        } elseif ($this->module->enableGeneratingPassword) {
            return Yii::t('user',
                'A message has been sent to your email address. It contains a password that we generated for you.');
        } elseif ($this->module->enableConfirmation) {
            return Yii::t('user',
                'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.');
        } else {
            return Yii::t('user', 'Welcome! Registration is complete.');
        }
    }

    /** @inheritdoc */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /** @inheritdoc */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallUsers()
    {
        return $this->hasMany(MallUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopUsers()
    {
        return $this->hasMany(ShopUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function deleteAdmin()
    {
        if (Yii::$app->authManager->checkAccess($this->id, 'administrator')) {
            Yii::$app->authManager->revoke(Yii::$app->authManager->getRole('administrator'), $this->id);
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole('user'), $this->id);
        }

        Yii::getLogger()->log('Revoke administrator role from' . $this->email, Logger::LEVEL_ERROR);

        return true;
    }
}
