<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\modules\user\helpers\Password;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 */
class LoginForm extends Model
{
    /** @var string User's email */
    public $login;

    /** @var string User's plain password */
    public $password;

    /** @var string Whether to remember the user */
    public $rememberMe = false;

    /** @var \app\modules\user\models\User */
    protected $user;

    /** @var \app\modules\user\Module */
    protected $module;

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->module = Yii::$app->getModule('user');
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('user', 'E-mail'),
            'password' => Yii::t('user', 'Password'),
            'rememberMe' => Yii::t('user', 'Remember me next time'),
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],

            ['login', 'email'],
            ['login', 'trim'],

            [
                'password',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                    }
                }
            ],

            [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        $confirmationRequired = $this->module->enableConfirmation && !$this->module->enableUnconfirmedLogin;
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
                        }
                    }
                }
            ],

            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);
        } else {
            return false;
        }
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'login-form';
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = User::find()->where(['email' => $this->login])->one();

            return true;
        } else {
            return false;
        }
    }
}
