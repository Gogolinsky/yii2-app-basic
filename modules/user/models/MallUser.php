<?php

namespace app\modules\user\models;

use app\modules\mall\models\Mall;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mall_user}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $blocked_at
 * @property integer $mall_id
 * @property integer $user_id
 *
 * @property Mall $mall
 * @property User $user
 */
class MallUser extends ActiveRecord
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mall_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['user_id'], 'unique', 'targetAttribute' => ['mall_id', 'user_id']],
            [['blocked_at', 'mall_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'created_at' => Yii::t('user', 'Created at'),
            'updated_at' => Yii::t('user', 'Updated at'),
            'blocked_at' => Yii::t('user', 'Blocked at'),
            'mall_id' => Yii::t('user', 'Mall ID'),
            'user_id' => Yii::t('user', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMall()
    {
        return $this->hasOne(Mall::className(), ['id' => 'mall_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }
}
