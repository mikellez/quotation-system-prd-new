<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Quotation;

/**
 * QuotationSearch represents the model behind the search form of `common\models\Quotation`.
 */
class QuotationSearch extends Quotation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quotation_id', 'rev_no', 'client', 'status', 'status_by', 'status_at', 'master', 'slave', 'created_at', 'updated_at', 'updated_by'], 'integer'],
            [['doc_no', 'doc_name', 'doc_title', 'project_name', 'reason', 'company', 'code', 'address', 'person', 'email', 'telephone', 'mobile', 'created_by', 'approved_by'], 'safe'],
            [['total_price', 'total_price_after_disc', 'max_total_price_after_disc', 'total_discount', 'total_discount2', 'total_discount_value', 'max_total_discount_value', 'accumulate_discount_rate', 'max_accumulate_discount_rate'], 'number'],
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
        $query = Quotation::find();
        $query = $query->joinWith(['createdBy createdBy', 'approvedBy approvedBy']);

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
            $query->andFilterWhere(['in', 'quotation.id', $ids]);
        } else {
            $query->andFilterWhere(['=', 'quotation.id', $this->id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'quotation_id' => $this->quotation_id,
            'rev_no' => $this->rev_no,
            'total_price' => $this->total_price,
            'total_price_after_disc' => $this->total_price_after_disc,
            'max_total_price_after_disc' => $this->max_total_price_after_disc,
            'total_discount' => $this->total_discount,
            'total_discount2' => $this->total_discount2,
            'total_discount_value' => $this->total_discount_value,
            'max_total_discount_value' => $this->max_total_discount_value,
            'accumulate_discount_rate' => $this->accumulate_discount_rate,
            'max_accumulate_discount_rate' => $this->max_accumulate_discount_rate,
            'client' => $this->client,
            'quotation.status' => $this->status,
            'status_by' => $this->status_by,
            'status_at' => $this->status_at,
            'master' => $this->master,
            'slave' => $this->slave,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'doc_no', $this->doc_no])
            ->andFilterWhere(['like', 'doc_name', $this->doc_name])
            ->andFilterWhere(['like', 'doc_title', $this->doc_title])
            ->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'person', $this->person])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'createdBy.username', $this->created_by])
            ->andFilterWhere(['like', 'approvedBy.username', $this->approved_by]);

        $query->andFilterWhere(['=', 'quotation.active', 1]);

        return $dataProvider;
    }
}
