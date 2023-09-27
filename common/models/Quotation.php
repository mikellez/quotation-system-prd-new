<?php

namespace common\models;

use phpDocumentor\Reflection\Types\Nullable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%quotation}}".
 *
 * @property int $id
 * @property string $doc_no
 * @property int|null $status
 * @property int|null $status_by
 * @property int|null $status_at
 * @property string|null $reason
 * @property string|null $address
 * @property string|null $person
 * @property string|null $email
 * @property string|null $telephone
 * @property string|null $mobile
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property User $statusBy
 * @property User $updatedBy
 */
class Quotation extends \yii\db\ActiveRecord
{
    const STATUS_REJECT = -1;
    const STATUS_CANCEL = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PENDING = 2;
    const STATUS_APPROVE = 10;
    const STATUS_DONE = 11;
    const STATUS_CONFIRM = 12;

    public $fullRevNo;
    public $dummy_doc_name;
    public $dummy_company;
    public $dummy_payment_tnc;
    public $user_company;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quotation}}';
    }

    public function behaviors() {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['doc_no'], 'required'],
            [['doc_no', 'doc_name', 'doc_title', 'company', 'client', 'code', 'address', 'payment_tnc', 'email', 'mobile'], 'required'],
            [['client', 'company', 'active', 'status', 'status_by', 'status_at', 'created_at', 'updated_at', 'created_by', 'updated_by','approved_by','approved_at'], 'integer'],
            [['code', 'reason', 'address', 'payment_tnc'], 'string'],
            [['doc_no', 'doc_name' , 'doc_title', 'project_name', 'person', 'email', 'telephone', 'mobile'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approved_by' => 'id']],
            [['status_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['status_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['fullRevNo'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doc_no' => 'Doc No',
            'fullRevNo' => 'Rev No',
            'doc_name' => 'Other Categories',
            'dummy_doc_name' => 'Categories',
            'doc_title' => 'Quotation Title',
            'project_name' => 'Project Name',
            'active' => 'Active',
            'status' => 'Status',
            'status_by' => 'Status By',
            'status_at' => 'Status At',
            'client' => 'Client',
            'company' => 'Company',
            'dummy_company' => 'Choose company',
            'code' => 'Code',
            'reason' => 'Reason',
            'address' => 'Address',
            'payment_tnc' => 'Payment TnC',
            'dummy_payment_tnc' => 'Choose Payment Tnc',
            'person' => 'Customer Name',
            'email' => 'Email',
            'telephone' => 'Telephone',
            'mobile' => 'Mobile',
            'total_amount' => 'Doc Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'approved_at' => 'Approved At',
            'created_by' => 'Sales Person',
            'updated_by' => 'Updated By',
            'approved_by' => 'Approved By',
        ];
    }

    public function fields() {
        return array_merge(parent::fields(), [
            'fullRevNo' => function() {
                return 'R'.$this->rev_no;
            }
        ]);
    }

     /**
     * Gets query for [[Quotation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuotationQuery
     */
    public function getQuotation()
    {
        return $this->hasOne(Quotation::className(), ['id' => 'quotation_id'])->onCondition(['active'=>1]);
    }

     /**
     * Gets query for [[Quotation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuotationQuery
     */
    public function getLinkQuotation()
    {
        return $this->hasOne(Quotation::className(), ['quotation_id' => 'id'])->onCondition(['active'=>1]);
    }

     /**
     * Gets query for [[Client0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ClientQuery
     */
    public function getItem()
    {
        return $this->hasMany(QuotationItem::className(), ['quotation_id' => 'id'])->onCondition(['active'=>1]);
    }

     /**
     * Gets query for [[Client0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ClientQuery
     */
    public function getLinkQuotationItem()
    {
        return $this->hasMany(QuotationItem::className(), ['link_quotation_id' => 'id'])->onCondition(['active'=>1]);
    }

     /**
     * Gets query for [[Client0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ClientQuery
     */
    public function getClient0()
    {
        return $this->hasOne(Client::className(), ['id' => 'client']);
    }

     /**
     * Gets query for [[Company0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CompanyQuery
     */
    public function getCompany0()
    {
        return $this->hasOne(Company::className(), ['id' => 'company']);
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
     * Gets query for [[ApprovedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'approved_by']);
    }

    /**
     * Gets query for [[StatusBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getStatusBy()
    {
        return $this->hasOne(User::className(), ['id' => 'status_by']);
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

    public function getClientList()
    {
        return ArrayHelper::map(Client::find()->orderBy(['company' => SORT_ASC])->asArray()->all(), 'id', 'company');
    }

    public function getCompanyList()
    {
        return ArrayHelper::map(Company::find()->asArray()->all(), 'id', 'company');
    }

    public function getPaymentTncList($company)
    {
        return ArrayHelper::map(CompanyDetail::find()->where(['company'=>$company])->asArray()->all(), 'payment_tnc', 'payment_tnc');
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_APPROVE => 'Approve', 
            self::STATUS_PENDING => 'Pending',
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_REJECT => 'Reject',
            self::STATUS_CANCEL => 'Cancel',
            self::STATUS_DONE => 'Done',
            self::STATUS_CONFIRM => 'Confirm',
        ];
    }

    public function getAccumulateDiscountRate($id) {
        return 10;
    }

    public function save($runValidation = true, $attributeNames = null) 
    {
        $transaction = Yii::$app->db->beginTransaction();

        $this->status_at = time();

        $modelQuotation = Quotation::findOne($this->id);
        $modelQuotationItem = $modelQuotation->item ?? false;

        //$this->rev_no += 1;

        /*if(!empty($modelQuotationItem) && !$modelQuotation->master) {
            $total_discount_value = 0;
            $total_discount_value2 = 0;
            $total_price = 0;
            $total_cost = 0;
            $total_discount = 0;
            $total_discount2 = 0;
            $total_price_after_disc = 0;
            $max_total_discount_value = 0;
            $max_total_discount_value2 = 0;
            $max_total_price_after_disc = 0;
            $total_agent_comm = 0;

            foreach($modelQuotationItem as $item) {
                $price = $item->retail_base_price;
                $agent_comm = $item->agent_comm;
                $standard_costing = $item->standard_costing;
                $qty = $item->quantity;
                $amt = $price*$qty;
                $threshold_discount = $item->threshold_discount;
                $discount = $item->discount;
                $discount2 = $item->discount2;
                $discount_value = $amt*$discount/100;
                $discount_value2 = $discount_value*$discount2/100;
                $max_discount_value = ($amt*$threshold_discount/100);
                $max_discount_value2 = $max_discount_value*$discount2/100;
                $price_after_disc = $amt - $discount_value - $discount_value2;
                $max_price_after_disc = $amt - $max_discount_value - $max_discount_value2;
                $total_discount += $discount;
                $total_discount2 += $discount2;
                $total_discount_value += $discount_value;
                $total_discount_value2 += $discount_value2;
                $total_price += $amt;
                $total_cost += $standard_costing;
                $total_price_after_disc += $price_after_disc;
                $max_total_discount_value += $max_discount_value;
                $max_total_discount_value2 += $max_discount_value2;
                $max_total_price_after_disc += $max_price_after_disc;
                $total_agent_comm += $price_after_disc * $agent_comm/100;
            }


            $this->total_discount = $total_discount;
            $this->total_discount2 = $total_discount2;
            $this->total_discount_value = $total_discount_value;
            $this->total_discount_value2 = $total_discount_value2;
            $this->total_price = $total_price;
            $this->total_cost = $total_cost;
            $this->total_price_after_disc = $total_price_after_disc;
            $this->accumulate_discount_rate = $total_discount_value > 0 ? ($total_discount_value/$total_price)*100 : 0;
            $this->accumulate_discount_rate2 = $total_discount_value2 > 0 ? ($total_discount_value2/$total_price)*100 : 0;
            $this->max_total_price_after_disc = $max_total_price_after_disc;
            $this->max_total_discount_value2 = $max_total_discount_value2;
            $this->max_accumulate_discount_rate2 = ($max_total_discount_value2/$total_price)*100;
            $this->total_agent_comm = $total_agent_comm;

        }*/

        if($this->doc_no=="") {
            if($this->rev_no == 1) {
                $modelMasterQuotation = Quotation::findOne(Yii::$app->request->get('id'));
                $masterDocNo = $modelMasterQuotation->doc_no;
                $masterDocNo = strpos($masterDocNo, '/') > 0 ? str_replace(substr($masterDocNo, strpos($masterDocNo, '/')), "", $masterDocNo) : $masterDocNo;

                $doc_type = Yii::$app->db->createCommand("select * from doc_type where type='QUO4' and doc_no='".$masterDocNo."'")->queryOne();
                if(empty($doc_type)) {
                    $runno = 1;
                    $result = Yii::$app->db->createCommand()->insert("doc_type", [
                        'type' => 'QUO4',
                        'runno'=> $runno,
                        'doc_no'=> $masterDocNo
                    ])->execute();
                } else {
                    $runno = $doc_type['runno']+ 1;
                    $result = Yii::$app->db->createCommand()->update("doc_type", [
                        'runno'=> $runno,
                        'doc_no'=> $masterDocNo
                    ], 'type = "QUO4"')->execute();
                    
                }


                $separator = [$masterDocNo,str_pad($runno, 2, "R", STR_PAD_LEFT)];

                $this->doc_no = implode("/", $separator);
                $this->save();
            }else if($this->master) {
                $doc_type = Yii::$app->db->createCommand("select * from doc_type where type='QUO2' and yrmth='".date('y')."'")->queryOne();
                if(empty($doc_type)) {
                    $runno = 1;
                    $result = Yii::$app->db->createCommand()->insert("doc_type", [
                        'type' => 'QUO2',
                        'runno'=> $runno,
                        'yrmth'=> date('y'),
                    ])->execute();
                } else {
                    $runno = $doc_type['runno']+ 1;
                    $result = Yii::$app->db->createCommand()->update("doc_type", [
                        'runno'=> $runno,
                        'yrmth'=> date('y'),
                    ], 'type = "QUO2"')->execute();
                    
                }
                $separator = ['QUO'.date('y'),$this->code,str_pad($runno, 4, "0", STR_PAD_LEFT)];

                $this->doc_no = implode("/", $separator);
                $this->save();
            } else if($this->slave) {
                $modelMasterQuotation = Quotation::findOne(Yii::$app->request->get('id'));

                $doc_type = Yii::$app->db->createCommand("select * from doc_type where type='QUO3' and doc_no='".$modelMasterQuotation->doc_no."'")->queryOne();
                if(empty($doc_type)) {
                    $runno = 1;
                    $result = Yii::$app->db->createCommand()->insert("doc_type", [
                        'type' => 'QUO3',
                        'runno'=> $runno,
                        'doc_no'=> $modelMasterQuotation->doc_no
                    ])->execute();
                } else {
                    $runno = $doc_type['runno']+ 1;
                    $result = Yii::$app->db->createCommand()->update("doc_type", [
                        'runno'=> $runno,
                        'doc_no'=> $modelMasterQuotation->doc_no
                    ], 'type = "QUO3"')->execute();
                    
                }
                $separator = [$modelMasterQuotation->doc_no,str_pad($runno, 2, "0", STR_PAD_LEFT)];

                $this->doc_no = implode("/", $separator);
                $this->save();
            } else if($this->doc_type == 'project') {
                $doc_type = Yii::$app->db->createCommand("select * from doc_type where type='QUO5'")->queryOne();
                if(empty($doc_type)) {
                    $runno = 1;
                    $result = Yii::$app->db->createCommand()->insert("doc_type", [
                        'type' => 'QUO5',
                        'runno'=> $runno
                    ])->execute();
                } else {
                    $runno = $doc_type['runno']+ 1;
                    $result = Yii::$app->db->createCommand()->update("doc_type", [
                        'runno'=> $runno,
                    ], 'type = "QUO5"')->execute();
                    
                }
                $this->doc_no = 'QUO'.date('Ym').str_pad($runno, 5, "0", STR_PAD_LEFT);
                $this->save();

            } else if($this->doc_type == 'quotation') {
                if($this->doc_type2 == 'combine') {
                    $doc_type = Yii::$app->db->createCommand("select * from doc_type where type='QUOCOM'")->queryOne();
                    if(empty($doc_type)) {
                        $runno = 1;
                        $result = Yii::$app->db->createCommand()->insert("doc_type", [
                            'type' => 'QUOCOM',
                            'runno'=> $runno
                        ])->execute();
                    } else {
                        $runno = $doc_type['runno']+ 1;
                        $result = Yii::$app->db->createCommand()->update("doc_type", [
                            'runno'=> $runno,
                        ], 'type = "QUOCOM"')->execute();
                        
                    }
                    $this->doc_no = 'QUOCOM'.date('Ym').str_pad($runno, 5, "0", STR_PAD_LEFT);

                } else {
                    $doc_type = Yii::$app->db->createCommand("select * from doc_type where type='QUO'")->queryOne();
                    if(empty($doc_type)) {
                        $runno = 1;
                        $result = Yii::$app->db->createCommand()->insert("doc_type", [
                            'type' => 'QUO',
                            'runno'=> $runno
                        ])->execute();
                    } else {
                        $runno = $doc_type['runno']+ 1;
                        $result = Yii::$app->db->createCommand()->update("doc_type", [
                            'runno'=> $runno,
                        ], 'type = "QUO"')->execute();
                        
                    }
                    $this->doc_no = 'QUO'.date('Ym').str_pad($runno, 5, "0", STR_PAD_LEFT);

                }
                $this->save();

            } else {
                $doc_type = Yii::$app->db->createCommand("select * from doc_type where type='QUO'")->queryOne();
                if(empty($doc_type)) {
                    $runno = 1;
                    $result = Yii::$app->db->createCommand()->insert("doc_type", [
                        'type' => 'QUO',
                        'runno'=> $runno
                    ])->execute();
                } else {
                    $runno = $doc_type['runno']+ 1;
                    $result = Yii::$app->db->createCommand()->update("doc_type", [
                        'runno'=> $runno,
                    ], 'type = "QUO"')->execute();
                    
                }
                $this->doc_no = 'QUO'.date('Ym').str_pad($runno, 5, "0", STR_PAD_LEFT);
                $this->save();

            }
        }

        $this->status_by = $this->updated_by;

        $ok = parent::save($runValidation, $attributeNames);
        if(!$ok)
        {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return $ok;
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\QuotationQuery(get_called_class());
    }

    public static function getTotalAgentCommissionPercentage($provider) {
        $total_agent_commision = 0;
        $total_price_after_disc = 0;

        foreach($provider as $item) {
            $total_agent_commision += $item->total_agent_comm;
            $total_price_after_disc += $item->total_price_after_disc;
        }

        return $total_price_after_disc > 0 ? number_format($total_agent_commision / $total_price_after_disc,2) : 0;
    }

    public static function getTotalGrossProfit($provider) {
        $total_agent_commision = 0;
        $total_price_after_disc = 0;
        $total_cost = 0;

        foreach($provider as $item) {
            $total_agent_commision += $item->total_agent_comm;
            $total_price_after_disc += $item->total_price_after_disc;
            $total_cost += $item->total_cost*$item->quantity;
        }

        return $total_price_after_disc > 0 ? number_format(($total_price_after_disc - $total_cost - $total_agent_commision) / $total_price_after_disc, 2) : 0;
    }
}
