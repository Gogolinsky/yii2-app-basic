<?php

namespace app\modules\message\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class MessageSearch
 * @package app\modules\message\models
 */
class MessageSearch extends Message
{
    public function rules()
    {
        return [
            ['id', 'integer'],
            [['alias', 'title'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Message::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);
        return $dataProvider;
    }
}
