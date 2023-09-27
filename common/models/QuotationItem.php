<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%quotation_item}}".
 *
 * @property int $id
 * @property int|null $quotation_id
 * @property int|null $product_id
 * @property string $name
 * @property string|null $brand_name
 * @property int|null $type
 * @property int|null $code
 * @property string|null $image
 * @property int|null $project_currency
 * @property float|null $retail_base_price
 * @property float|null $project_base_price
 * @property float|null $threshold_discount
 * @property float|null $project_threshold_discount
 * @property float|null $admin_discount
 * @property float|null $standard_costing
 * @property string $description
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property Products $product
 * @property Currencies $projectCurrency
 * @property Quotation $quotation
 * @property Type $type0
 * @property User $updatedBy
 */
class QuotationItem extends \yii\db\ActiveRecord
{
    public $doc_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quotation_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quotation_id', 'link_quotation_id', 'product_id', 'quantity', 'type', 'project_currency', 'active', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description'], 'required'],
            [['quantity', 'price', 'retail_base_price', 'project_base_price', 'threshold_discount', 'project_threshold_discount', 'admin_discount', 'standard_costing', 'discount', 'discount2', 'agent_comm'], 'number'],
            [['description'], 'string'],
            [['quantity'], 'default', 'value' => 1],
            [['discount'], 'default', 'value' => 0.00],
            [['discount2'], 'default', 'value' => 0.00],
            [['discountrm'], 'default', 'value' => 0.00],
            [['code', 'name', 'brand_name', 'image'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['project_currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['project_currency' => 'id']],
            [['quotation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quotation::className(), 'targetAttribute' => ['quotation_id' => 'id']],
            [['link_quotation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quotation::className(), 'targetAttribute' => ['link_quotation_id' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['type' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotation_id' => 'Quotation ID',
            'link_quotation_id' => 'Link Quotation ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'name' => 'Name',
            'brand_name' => 'Brand Name',
            'type' => 'Type',
            'code' => 'Code',
            'image' => 'Image',
            'project_currency' => 'Project Currency',
            'price' => 'Price',
            'retail_base_price' => 'Retail Base Price',
            'project_base_price' => 'Project Base Price',
            'threshold_discount' => 'Threshold Discount',
            'project_threshold_discount' => 'Project Threshold Discount',
            'admin_discount' => 'Admin Discount',
            'standard_costing' => 'Standard Costing',
            'discount' => 'Discount',
            'discount2' => 'Discount 2',
            'discountrm' => 'Discount RM',
            'agent_comm' => 'Agent Commission',
            'description' => 'Description',
            'active' => 'Active',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public function save($runValidation = true, $attributeNames = null) 
    {
	if($this->product_parent_id == 0) {
		$discount = $this->discount ? $this->discount/100 : 0;
		$discountrm = $this->discountrm ?? 0;
		$admin_disc = $this->admin_discount ? $this->admin_discount/100 : 0;
		$rbp = $this->retail_base_price ?? 0;
		$qty = $this->quantity ?? 1;
		$cost = $this->standard_costing*$qty ?? 0;
		$price = $rbp * $qty;

		$discount2 = $discountrm > 0 ? ($price - ($discountrm))/$price : 0;
		//$price_after_disc = ($price - ($price * $discount) - ($discountrm*$qty) - ($price * $admin_disc));
		$price_after_disc = ($price - ($price * $discount) - ($price * $admin_disc));
		//$retail_base_price_after_disc = ($rbp - ($rbp * $discount) - $discountrm - ($rbp * $admin_disc));
		$retail_base_price_after_disc = ($rbp - ($rbp * $discount) - ($rbp * $admin_disc));

		$discount_value = $price*$discount;
		$admin_discount_value = $price*$admin_disc; 
		$discountrm_value = $discountrm > 0 ? $discountrm*$qty : 0; 
		$margin = $price_after_disc > 0 ? (($price_after_disc - $cost)/$price_after_disc)*100 : 100;

		$this->price = $price;
		$this->price_after_disc = $price_after_disc;
		$this->retail_base_price_after_disc = $retail_base_price_after_disc;
		$this->margin = $margin;

		$this->discount_value = $discount_value;
		$this->admin_discount_value = $admin_discount_value;
		$this->discountrm_value = $discountrm_value;
	}

        $ok = parent::save($runValidation, $attributeNames);
        if(!$this->quotation_id) return $ok;
	if($this->price <= 0) return $ok;
	if($this->product_parent_id > 0) return $ok;
        $modelQuotation = Quotation::findOne($this->quotation_id);
        //$modelQuotationItem = $modelQuotation->item ?? false;
        $modelQuotationItem = QuotationItem::find()->where(['active'=>1, 'quotation_id'=>$this->quotation_id, 'product_parent_id' => 0])->all();

        //if(!empty($modelQuotationItem)) {
            $total_discount_value = 0;
            //$total_discount_value2 = 0;
            $total_admin_discount = 0;
            $total_admin_discount_value = 0;
            $total_discountrm_value = 0;
            $total_price = 0;
            $total_retail_base_price = 0;
            $total_cost = 0;
            $total_margin = 0;
            $total_discount = 0;
            $total_discount2 = 0;
            $total_discountrm = 0;
            $total_price_after_disc = 0;
            $total_retail_base_price_after_disc = 0;
            $total_agent_comm = 0;
            $max_total_discount_value = 0;
            $max_total_admin_discount_value = 0;
            $max_total_discount_value2 = 0;
            $max_total_discountrm_value = 0;

            foreach($modelQuotationItem as $item) {
                $rbp = $item->retail_base_price;
                $qty = $item->quantity;
                $standard_costing = $item->standard_costing*$qty;
                $price = $rbp*$qty;
                $agent_comm = $item->agent_comm/100;
                $discount = $item->discount/100;
                $discountrm = $item->discountrm;
                $admin_disc = $item->admin_discount/100;
                $threshold_disc = $item->threshold_discount/100;
                $discount2 = $discountrm > 0 ? ($price - ($discountrm))/$rbp : 0;
                //$discount_value = $amt*$discount/100;
                $discount_value = $item->discount_value;//(($price * $discount) + ($discountrm * $qty)); 
                //$discount_value2 = $item->discount_value2;//(($price * $discount) + ($discountrm * $qty)); 
                $admin_discount_value = $item->admin_discount_value;//$price*$admin_disc; 
                $discountrm_value = $item->discountrm_value;//($price-($discountrm*$qty)); 
                $max_discount_value = $price*$threshold_disc/100; 
                $max_admin_discount_value = $price*$admin_disc/100; 
                $max_discount_value2 = $price*$admin_disc/100; 
                $max_discountrm_value = 0;
                //$price_after_disc = $amt - ($discount_value + $discount_value2 + $discountrm_value);
                $price_after_disc = $item->price_after_disc;//($price - ($price * $discount) - $discountrm - ($price * $discount2));
                $retail_base_price_after_disc = $item->retail_base_price_after_disc;//($price - ($price * $discount) - $discountrm - ($price * $discount2));
                $total_discount += $discount;
                $total_admin_discount += $admin_disc;
                $total_discount2 += $discount2;
                $total_discountrm += $discountrm;
                $total_discount_value += $discount_value;
                $total_admin_discount_value += $admin_discount_value;
                //$total_discount_value2 += $discount_value2;
                $total_discountrm_value += $discountrm_value;
                $max_total_discount_value += $max_discount_value;
                $max_total_admin_discount_value += $max_total_discount_value;
                $max_total_discount_value2 += $max_discount_value2;
                $max_total_discountrm_value += $max_discountrm_value;
                $total_price += $price;
                $total_retail_base_price += $rbp;
                $total_cost += $standard_costing;
                $total_price_after_disc += $price_after_disc;
                $total_retail_base_price_after_disc += $retail_base_price_after_disc;
                $total_agent_comm += $price_after_disc * $agent_comm;
            }

            $modelQuotation->total_discount = $total_discount;
            $modelQuotation->total_discount2 = $total_discount2;
            $modelQuotation->total_admin_discount = $total_admin_discount;
            $modelQuotation->total_discountrm = $total_discountrm;
            $modelQuotation->total_discount_value = $total_discount_value;
            $modelQuotation->total_admin_discount_value = $total_admin_discount_value;
            //$modelQuotation->total_discount_value2 = $total_discount_value2;
            $modelQuotation->total_discountrm_value = $total_discountrm_value;
            $modelQuotation->max_total_discount_value = $max_total_discount_value;
            $modelQuotation->max_total_admin_discount_value = $max_total_admin_discount_value;
            $modelQuotation->max_total_discount_value2 = $max_total_discount_value2;
            $modelQuotation->max_total_discountrm_value = $max_total_discountrm_value;
            $modelQuotation->max_total_price_after_disc = $total_price_after_disc;
            $modelQuotation->total_price = $total_price;
            $modelQuotation->total_retail_base_price = $total_retail_base_price;
            $modelQuotation->total_cost = $total_cost;
            $modelQuotation->total_margin = $total_price_after_disc == 0 ? 100 : (($total_price_after_disc - $total_cost) / $total_price_after_disc ) * 100;
            $modelQuotation->total_price_after_disc = $total_price_after_disc;
            $modelQuotation->total_retail_base_price_after_disc = $total_retail_base_price_after_disc;
            $modelQuotation->accumulate_discount_rate = $total_discount_value > 0 ? (($total_discount_value+$total_discountrm_value+$total_admin_discount_value)/$total_price)*100 : 0;
            //$modelQuotation->accumulate_discount_rate2 = $total_discount_value2 > 0 ? (($total_discount_value2)/$total_price)*100 : 0;
            $modelQuotation->accumulate_discountrm_rate = $total_discountrm_value > 0 ? (($total_discountrm_value)/$total_price)*100 : 0;
            $modelQuotation->total_agent_comm = $total_agent_comm;
            $modelQuotation->save();
            //print_r($modelQuotation->attributes);die;

            $childQuotations = Quotation::find()->where(['quotation_id'=>$modelQuotation->quotation_id, 'active'=>1])->all();

            $grand_total_discount = 0;
            $grand_total_discount2 = 0;
            $grand_total_discount_value = 0;
            $grand_total_discount_value2 = 0;
            $grand_max_total_discount_value = 0;
            $grand_max_total_discount_value2 = 0;
            $grand_total_price = 0;
            $grand_total_cost = 0;
            $grand_total_price_after_disc = 0;
            $grand_max_total_price_after_disc = 0;
            $grand_total_accumulate_discount_rate = 0;
            $grand_total_accumulate_discount_rate2 = 0;
            $grand_total_agent_comm = 0;

            foreach($childQuotations as $childQuotation) {
                $grand_total_price += $childQuotation->total_price;
                $grand_total_cost += $childQuotation->total_cost;
                $grand_total_price_after_disc += $childQuotation->total_price_after_disc;
                $grand_max_total_price_after_disc += $childQuotation->max_total_price_after_disc;
               //$grand_total_discount += $childQuotation->total_discount; 
               //$grand_total_discount2 += $childQuotation->total_discount2; 
               $grand_total_discount_value += $childQuotation->total_discount_value; 
               $grand_total_discount_value2 += $childQuotation->total_discount_value2; 
               $grand_max_total_discount_value += $childQuotation->max_total_discount_value; 
               $grand_max_total_discount_value2 += $childQuotation->max_total_discount_value2; 
               $grand_total_agent_comm += $total_agent_comm;
            }

            $parentmodelQuotation = Quotation::findOne($modelQuotation->quotation_id);

            if(!empty($parentmodelQuotation)) {
               $grand_total_accumulate_discount_rate = $grand_total_discount_value/$grand_total_price; 
               $grand_total_accumulate_discount_rate2 = $grand_total_discount_value2/$grand_total_price; 
               $grand_max_total_accumulate_discount_rate = $grand_max_total_discount_value/$grand_total_price; 
               $grand_max_total_accumulate_discount_rate2 = $grand_max_total_discount_value2/$grand_total_price; 

               $parentmodelQuotation->total_discount_value = $grand_total_discount_value;
               $parentmodelQuotation->total_discount_value2 = $grand_total_discount_value2;
               $parentmodelQuotation->max_total_discount_value = $grand_max_total_discount_value;
               $parentmodelQuotation->max_total_discount_value2 = $grand_max_total_discount_value2;
               $parentmodelQuotation->total_price = $grand_total_price;
               $parentmodelQuotation->total_cost = $grand_total_cost;
               $parentmodelQuotation->total_price_after_disc = $grand_total_price_after_disc;
               $parentmodelQuotation->accumulate_discount_rate = $grand_total_accumulate_discount_rate;
               $parentmodelQuotation->accumulate_discount_rate2 = $grand_total_accumulate_discount_rate2;
               $parentmodelQuotation->max_accumulate_discount_rate = $grand_max_total_accumulate_discount_rate;
               $parentmodelQuotation->max_accumulate_discount_rate2 = $grand_max_total_accumulate_discount_rate2;
               $parentmodelQuotation->total_agent_comm = $grand_total_agent_comm;
               //print_r($parentmodelQuotation->attributes);die;
               $parentmodelQuotation->save();
            }

        //}

        return $ok;

    }

    public function getImageUrl() 
    {
        $filePath = Yii::$app->params['backendUrl'].'/storage'.$this->image;
        if(!$this->image || !file_exists(Yii::getAlias('@backend/web/storage/'.$this->image))) {
            return Yii::$app->params['backendUrl'].'/img/noimg.jpeg';
        }
        return $filePath;
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductsQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[ProjectCurrency]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CurrenciesQuery
     */
    public function getProjectCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'project_currency']);
    }

    /**
     * Gets query for [[Quotation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuotationQuery
     */
    public function getQuotation()
    {
        return $this->hasOne(Quotation::className(), ['id' => 'quotation_id']);
    }

    /**
     * Gets query for [[Quotation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuotationQuery
     */
    public function getLinkQuotation()
    {
        return $this->hasOne(Quotation::className(), ['id' => 'link_quotation_id']);
    }

    /**
     * Gets query for [[Type0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TypeQuery
     */
    public function getType0()
    {
        return $this->hasOne(Type::className(), ['id' => 'type']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Brand]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\BrandQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['brand' => 'brand_name']);
    }

    public function getDocName(){
        return $this->doc_name = $this->linkQuotation->doc_name;
    } 

    /**
     * {@inheritdoc}
     * @return \common\models\query\QuotationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\QuotationQuery(get_called_class());
    }
}
