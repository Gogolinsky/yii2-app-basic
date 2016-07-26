<?php

namespace app\modules\user\models;

use app\modules\shop\models\Shop;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shop_user}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $blocked_at
 * @property integer $shop_id
 * @property integer $user_id
 *
 * @property \app\modules\shop\models\Shop $shop
 * @property User $user
 */
class ShopUser extends ActiveRecord
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
        return '{{%shop_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'user_id'], 'required'],
            [['user_id'], 'unique', 'targetAttribute' => ['shop_id', 'user_id']],
            [['blocked_at', 'shop_id', 'user_id'], 'integer']
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
            'shop_id' => Yii::t('user', 'Shop ID'),
            'user_id' => Yii::t('user', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
