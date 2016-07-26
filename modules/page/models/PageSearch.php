<?php

namespace app\modules\page\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class PageSearch
 * @package app\modules\page\models
 */
class PageSearch extends Page
{
    /** @var $parent */
    public $parent;

    /**
     * @return array
     */
    public static function getDropDown()
    {
        $list = [];
        $models = Page::find()->orderBy('tree, lft')->all();

        foreach ($models as $model) {
            if (!$model->isLeaf()) {
                $place = '';
                for ($i = 1; $i < $model->depth; $i++) {
                    $place .= '_';
                }
                $list[$model->id] = $place . $model->title;
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    public function pageFilter()
    {
        $result = [];
        $root = Page::findOne($this->parent);
        if (!is_null($root)) {
            $models = $root->children(1)->all();
            foreach ($models as $model) {
                $result[] = $model->id;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['title', 'string', 'max' => 255],
            ['alias', 'string', 'max' => 255],
            ['parent', 'integer'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Page::find();
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
        $query->andFilterWhere(['id' => $this->pageFilter()]);

        return $dataProvider;
    }
}
