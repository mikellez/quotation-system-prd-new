<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Brand;

/**
 * BrandSearch represents the model behind the search form of `common\models\Brand`.
 */
class BrandSearch extends Brand
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['brand'], 'safe'],
            [['less', 'mark_up', 'sales_tax', 'cost'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Brand::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'less' => $this->less,
            'mark_up' => $this->mark_up,
            'sales_tax' => $this->sales_tax,
            'cost' => $this->cost,
        ]);

        $query->andFilterWhere(['like', 'brand', $this->brand]);

        return $dataProvider;
    }
}
