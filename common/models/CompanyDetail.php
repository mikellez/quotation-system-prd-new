<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%company_detail}}".
 *
 * @property int $id
 * @property int|null $company
 * @property string|null $payment_tnc
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_by
 * @property int|null $updated_at
 *
 * @property Company $company0
 * @property User $createdBy
 * @property User $updatedBy
 */
class CompanyDetail extends \yii\db\ActiveRecord
{

    public $companyName;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_detail}}';
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
            [['company', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['payment_tnc'], 'string'],
            [['company'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'company' => 'Company',
            'companyName' => 'Company',
            'payment_tnc' => 'Payment Tnc',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Company0]].
     *
     * @return \yii\db\ActiveQuery|CompanyQuery
     */
    public function getCompany0()
    {
        return $this->hasOne(Company::className(), ['id' => 'company']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|CompanyQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|CompanyQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getPaymentTncList($company)
    {
        return ArrayHelper::map(CompanyDetail::find()->where(['company'=>$company])->asArray()->all(), 'payment_tnc', 'payment_tnc');
    }

    /**
     * {@inheritdoc}
     * @return CompanyDetailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyDetailQuery(get_called_class());
    }
}
