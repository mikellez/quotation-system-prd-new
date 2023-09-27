<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PointDoc;

/**
 * PointDocSearch represents the model behind the search form of `common\models\PointDoc`.
 */
class PointDocSearch extends PointDoc
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quotation_id', 'user_id_from', 'user_id_to', 'status', 'status_by', 'status_at', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['doc_no', 'doc_type', 'ref_no', 'remark'], 'safe'],
            [['sales_point_rate', 'total_sales_point', 'total_payment_received', 'total_debit_sales_point', 'bf', 'total_point'], 'number'],
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
        $query = PointDoc::find();

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
            'user_id_from' => $this->user_id_from,
            'user_id_to' => $this->user_id_to,
            'sales_point_rate' => $this->sales_point_rate,
            'total_sales_point' => $this->total_sales_point,
            'total_payment_received' => $this->total_payment_received,
            'total_debit_sales_point' => $this->total_debit_sales_point,
            'bf' => $this->bf,
            'total_point' => $this->total_point,
            'status' => $this->status,
            'status_by' => $this->status_by,
            'status_at' => $this->status_at,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'doc_no', $this->doc_no])
            ->andFilterWhere(['like', 'doc_type', $this->doc_type])
            ->andFilterWhere(['like', 'ref_no', $this->ref_no])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
