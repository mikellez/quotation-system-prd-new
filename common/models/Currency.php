<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%currencies}}".
 *
 * @property int $id
 * @property string $currency
 *
 * @property Products[] $products
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%currencies}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['currency'], 'required'],
            [['currency'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency' => 'Currency',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductsQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['project_currency' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CurrencyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CurrencyQuery(get_called_class());
    }
}
