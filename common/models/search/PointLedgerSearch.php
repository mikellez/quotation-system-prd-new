<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PointLedger;

/**
 * PointLedgerSearch represents the model behind the search form of `common\models\PointLedger`.
 */
class PointLedgerSearch extends PointLedger
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id_from', 'user_id_to', 'doc_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['type', 'action', 'ref_no', 'remark'], 'safe'],
            [['debit', 'credit', 'balance'], 'number'],
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
        $query = PointLedger::find();

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
            'user_id_from' => $this->user_id_from,
            'user_id_to' => $this->user_id_to,
            'doc_id' => $this->doc_id,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'balance' => $this->balance,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'ref_no', $this->ref_no])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
