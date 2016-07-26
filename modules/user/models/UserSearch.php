<?php

namespace app\modules\user\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    /** @var string */
    // public $username;

    /** @var string */
    public $email;

    /** @var int */
    public $created_at;

    /** @var string */
    public $registration_ip;

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            //[['username'], 'safe'],
            [['email', 'registration_ip', 'created_at'], 'safe'],
            ['created_at', 'default', 'value' => null],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('user', 'Email'),
            'created_at' => \Yii::t('user', 'Registration time'),
            'registration_ip' => \Yii::t('user', 'Registration ip'),
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider(compact('query'));

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}
