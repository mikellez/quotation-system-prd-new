<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuotationItem;

/**
 * QuotationItemSearch represents the model behind the search form of `common\models\QuotationItem`.
 */
class QuotationItemSearch extends QuotationItem
{
	public $formNameParam = "QuotationItemSearch";

    /**
     * @return string — the form name of this model class.
     */
    public function formName() {
        return $this->formNameParam;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quotation_id', 'link_quotation_id', 'product_id', 'quantity', 'type', 'project_currency', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['code', 'name', 'brand_name', 'image', 'description', 'product_type'], 'safe'],
            [['retail_base_price', 'project_base_price', 'threshold_discount', 'project_threshold_discount', 'admin_discount', 'standard_costing'], 'number'],
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
        $query = QuotationItem::find()->where(['quotation_id'=>$params['quotation_id']]);

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
            'quotation_id' => $this->quotation_id,
            'link_quotation_id' => $this->link_quotation_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'project_currency' => $this->project_currency,
            'retail_base_price' => $this->retail_base_price,
            'project_base_price' => $this->project_base_price,
            'threshold_discount' => $this->threshold_discount,
            'project_threshold_discount' => $this->project_threshold_discount,
            'admin_discount' => $this->admin_discount,
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
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'description', $this->description]);

        $query->andFilterWhere(['=', 'active', 1]);

        return $dataProvider;
    }
}
