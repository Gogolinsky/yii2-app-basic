<?php

namespace app\modules\settings\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property integer $id
 * @property string $email_callback
 * @property string $email_noreply
 * @property string $yandex_webmaster
 * @property string $google_webmaster
 * @property string $metrika_code
 * @property string $analitics_code
 */
class Settings extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    public static function getNoReplyEmail()
    {
        $model = Settings::findOne(1);

        if (null !== $model) {
            return $model->email_noreply;
        }

        return null;
    }

    public static function getCallbackEmail()
    {
        $model = Settings::findOne(1);

        if (null !== $model) {
            return $model->email_callback;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email_callback', 'string'],
            ['email_noreply', 'string'],
            ['metrika_code', 'string'],
            ['analitics_code', 'string'],
            ['yandex_webmaster', 'string'],
            ['google_webmaster', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email_callback' => Yii::t('settings', 'Call back email'),
            'email_noreply' => Yii::t('settings', 'No reply email'),
            'yandex_webmaster' => Yii::t('settings', 'Yandex webmaster meta tag'),
            'google_webmaster' => Yii::t('settings', 'Google webmaster meta tag'),
            'metrika_code' => Yii::t('settings', 'Yandex metrika code'),
            'analitics_code' => Yii::t('settings', 'Google analitics code'),
        ];
    }
}
