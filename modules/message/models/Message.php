<?php

namespace app\modules\message\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\log\Logger;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $alias
 * @property string $title
 * @property resource $content
 */
class Message extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
            ['alias', 'required'],
            ['alias', 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'integer'],
            ['content', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('message', 'ID'),
            'created_at' => Yii::t('message', 'Created at'),
            'updated_at' => Yii::t('message', 'Updated at'),
            'alias' => Yii::t('message', 'Alias'),
            'title' => Yii::t('message', 'Title'),
            'content' => Yii::t('message', 'Content'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'create' => ['created_at', 'updated_at', 'title', 'alias', 'content'],
            'update' => ['updated_at', 'title', 'alias', 'content'],
        ];
    }

    /**
     * This method is used to create new message.
     *
     * @return bool
     */
    public function create()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing model');
        }

        if ($this->save()) {
            Yii::getLogger()->log('Message has been created', Logger::LEVEL_INFO);

            return true;
        }

        Yii::getLogger()->log('An error occurred while creating message', Logger::LEVEL_ERROR);

        return false;
    }
}
