<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_component".
 *
 * @property int $id
 * @property int|null $products_id
 * @property int|null $product_component_id
 * @property int|null $qty
 *
 * @property Products $products
 */
class ProductComponent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_component';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['products_id', 'product_component_id', 'qty'], 'integer'],
            [['products_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['products_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'products_id' => 'Products ID',
            'product_component_id' => 'Product Component ID',
            'qty' => 'Qty',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductsQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Product::className(), ['id' => 'products_id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductsQuery
     */
    public function getProductComponent()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_component_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProductComponentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProductComponentQuery(get_called_class());
    }
}
