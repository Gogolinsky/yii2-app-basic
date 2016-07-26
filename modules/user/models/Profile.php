<?php

namespace app\modules\user\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $user_id
 * @property string $name
 * @property string $last_name
 * @property string $public_email
 * @property string $gravatar_email
 * @property string $gravatar_id
 * @property string $location
 * @property string $website
 * @property string $bio
 */
class Profile extends ActiveRecord
{
    /** @var \app\modules\user\Module */
    protected $module;

    /** @inheritdoc */
    public function init()
    {
        $this->module = \Yii::$app->getModule('user');
    }

    /** @inheritdoc */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['bio', 'string'],

            ['public_email', 'email'],
            ['public_email', 'string', 'max' => 255],

            ['gravatar_email', 'email'],
            ['gravatar_email', 'string', 'max' => 255],

            ['website', 'url'],
            ['website', 'string', 'max' => 255],

            ['name', 'string', 'max' => 255],

            ['last_name', 'string', 'max' => 255],

            ['location', 'string', 'max' => 255],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('user', 'Name'),
            'last_name' => \Yii::t('user', 'Last name'),
            'public_email' => \Yii::t('user', 'Email (public)'),
            'gravatar_email' => \Yii::t('user', 'Gravatar email'),
            'location' => \Yii::t('user', 'Location'),
            'website' => \Yii::t('user', 'Website'),
            'bio' => \Yii::t('user', 'Bio'),
        ];
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isAttributeChanged('gravatar_email')) {
                $this->setAttribute('gravatar_id', md5(strtolower($this->getAttribute('gravatar_email'))));
            }

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }
}
