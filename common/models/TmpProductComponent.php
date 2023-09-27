<?php

namespace common\models;

use Yii;
use common\models\TmpProducts;

/**
 * This is the model class for table "tmp_product_component".
 *
 * @property int $id
 * @property int|null $products_id
 * @property int|null $product_component_id
 * @property int|null $qty
 *
 * @property Products $products
 */
class TmpProductComponent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tmp_product_component';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['products_id', 'product_component_id', 'qty'], 'integer'],
            //[['products_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['products_id' => 'id']],
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
     * @return \yii\db\ActiveQuery|\common\models\query\TmpProductsQuery
     */
    public function getProducts()
    {
        return $this->hasOne(TmpProducts::className(), ['id' => 'products_id']);
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
     * @return \common\models\query\TmpProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TmpProductsQuery(get_called_class());
    }
}
