<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%point_doc}}".
 *
 * @property int $id
 * @property int|null $quotation_id
 * @property int|null $user_id_from
 * @property int|null $user_id_to
 * @property string|null $doc_no
 * @property string|null $doc_type
 * @property string|null $ref_no
 * @property string|null $remark
 * @property float|null $sales_point_rate
 * @property float|null $total_sales_point
 * @property float|null $total_payment_received
 * @property float|null $total_debit_sales_point
 * @property float|null $bf
 * @property float|null $total_point
 * @property int|null $status
 * @property int|null $status_by
 * @property int|null $status_at
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_by
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property Quotation $quotation
 * @property User $statusBy
 * @property User $updatedBy
 * @property User $userIdFrom
 * @property User $userIdTo
 */
class PointDoc extends \yii\db\ActiveRecord
{
    const STATUS_REJECT = -1;
    const STATUS_CANCEL = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PENDING = 2;
    const STATUS_APPROVE = 10;
    const STATUS_DONE = 11;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%point_doc}}';
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
            [['quotation_id', 'user_id', 'user_id_from', 'user_id_to', 'status', 'status_by', 'status_at', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['sales_point_rate', 'total_sales_point', 'total_payment_received', 'total_debit_sales_point', 'bf', 'total_point'], 'number'],
            [['doc_no', 'doc_type', 'ref_no', 'remark'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['quotation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quotation::className(), 'targetAttribute' => ['quotation_id' => 'id']],
            [['status_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['status_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['user_id_from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id_from' => 'id']],
            [['user_id_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id_to' => 'id']],
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
            'user_id' => 'User Id',
            'user_id_from' => 'User Id From',
            'user_id_to' => 'User Id To',
            'doc_no' => 'Doc No',
            'doc_type' => 'Doc Type',
            'ref_no' => 'Ref No',
            'remark' => 'Remark',
            'sales_point_rate' => 'Sales Point',
            'total_sales_point' => 'Total Sales Point',
            'total_payment_received' => 'Total Payment Received',
            'total_debit_sales_point' => 'Total Debit Sales Point',
            'bf' => 'Bf',
            'total_point' => 'Total Point',
            'status' => 'Status',
            'status_by' => 'Status By',
            'status_at' => 'Status At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
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
     * Gets query for [[Quotation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\QuotationQuery
     */
    public function getQuotation()
    {
        return $this->hasOne(Quotation::className(), ['id' => 'quotation_id']);
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

    /**
     * Gets query for [[UserIdFrom]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUserIdFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id_from']);
    }

    /**
     * Gets query for [[UserIdTo]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUserIdTo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id_to']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PointDocQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PointDocQuery(get_called_class());
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
        ];
    }
}
