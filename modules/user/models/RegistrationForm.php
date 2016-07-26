<?php

namespace app\modules\user\models;

use app\modules\user\Module;
use Yii;
use yii\base\Model;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * Class RegistrationForm
 * @package app\modules\user\models
 *
 * @var string $email
 * @var string $name
 * @var string $lastName
 * @var string $password
 * @var User $user
 * @var Module $module
 */
class RegistrationForm extends Model
{
    public $email;
    public $name;
    public $lastName;
    public $phone;
    public $password;

    protected $user;
    protected $module;

    /** @inheritdoc */
    public function init()
    {
        $this->user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'register',
        ]);
        $this->module = Yii::$app->getModule('user');
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 3, 'max' => 50],
            ['lastName', 'filter', 'filter' => 'trim'],
            ['lastName', 'string', 'min' => 3, 'max' => 50],
            ['phone', 'required'],
            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'string', 'min' => 5, 'max' => 50],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'trim'],
            [
                'email',
                'unique',
                'targetClass' => $this->module->modelMap['User'],
                'message' => Yii::t('user', 'This email address has already been taken'),
            ],
            ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            ['password', 'string', 'min' => 6],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'E-mail'),
            'name' => Yii::t('user', 'Name'),
            'lastName' => Yii::t('user', 'Last name'),
            'phone' => Yii::t('user', 'Contact phone'),
            'password' => Yii::t('user', 'Password'),
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'register-form';
    }

    /**
     * Registers a new user account.
     *
     * @return bool
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user->setAttributes([
            'email' => $this->email,
            'password' => $this->password,
            'name' => $this->name,
            'lastName' => $this->lastName,
        ]);

        return $this->user->register();
    }
}
