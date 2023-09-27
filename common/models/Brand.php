<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string|null $brand
 * @property float|null $less
 * @property float|null $mark_up
 * @property float|null $sales_tax
 * @property float|null $cost
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['less', 'mark_up', 'sales_tax', 'cost'], 'number'],
            [['brand'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand' => 'Brand',
            'less' => 'Less',
            'mark_up' => 'Mark Up',
            'sales_tax' => 'Sales Tax',
            'cost' => 'Cost',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BrandQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BrandQuery(get_called_class());
    }
}
