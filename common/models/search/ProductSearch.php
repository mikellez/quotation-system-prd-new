<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_currency', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['code', 'name', 'brand_name', 'type', 'image', 'description', 'product_type'], 'safe'],
            [['retail_base_price', 'project_base_price', 'threshold_discount', 'standard_costing'], 'number'],
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
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        }

        // grid filtering conditions
        if(count($ids = explode(',',$this->id))>1) {
            /*foreach($ids as $id) {
                $query->orFilterWhere([
                    'id' => $this->id
                ]);
            }*/
            $query->andFilterWhere(['in', 'id', $ids]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id
            ]);
        }

        $query->andFilterWhere([
            //'code' => $this->code,
            'retail_base_price' => $this->retail_base_price,
            'project_currency' => $this->project_currency,
            'project_base_price' => $this->project_base_price,
            'threshold_discount' => $this->threshold_discount,
            'standard_costing' => $this->standard_costing,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'product_type', $this->product_type])
            ->andFilterWhere(['like', 'description', $this->description]);

	$query->orderBy([ 'brand_name'=> SORT_ASC, 'sequence'=> SORT_ASC ]);

        return $dataProvider;
    }
}
