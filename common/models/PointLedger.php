<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%point_ledger}}".
 *
 * @property int $id
 * @property int|null $user_id_from
 * @property int|null $user_id_to
 * @property int|null $doc_id
 * @property string|null $type
 * @property string|null $action
 * @property string|null $ref_no
 * @property float|null $debit
 * @property float|null $credit
 * @property float|null $balance
 * @property string|null $remark
 * @property string|null $description
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_by
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property Quotation $quotation
 * @property User $updatedBy
 * @property User $userIdFrom
 * @property User $userIdTo
 */
class PointLedger extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%point_ledger}}';
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
            [['user_id', 'user_id_from', 'user_id_to', 'doc_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['debit', 'credit', 'balance', 'accumulate_point'], 'number'],
            [['type', 'action', 'ref_no', 'remark', 'description'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => PointDoc::className(), 'targetAttribute' => ['doc_id' => 'id']],
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
            'user_id' => 'User Id',
            'user_id_from' => 'User Id From',
            'user_id_to' => 'User Id To',
            'doc' => 'Doc ID',
            'type' => 'Type',
            'action' => 'Action',
            'ref_no' => 'Ref No',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'balance' => 'Balance',
            'accumulate_point' => 'Accumulate Point',
            'remark' => 'Remark',
            'description' => 'Description',
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
    public function getDocId()
    {
        return $this->hasOne(PointDoc::className(), ['id' => 'doc_id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
     * @return \common\models\query\PointLedgerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PointLedgerQuery(get_called_class());
    }

    public function save($runValidation = true, $attributeNames = null) 
    {
        $ok = parent::save($runValidation, $attributeNames);

        if($ok) {
            //$modelDoc = PointDoc::findOne($this->doc_id);
            $modelPoint = Point::findOne(['user_id'=>$this->user_id]);

            $modelPoint->balance += $this->balance;
            $modelPoint->save();

            /*if(!empty($modelDoc)) {
                $modelDoc->bf = $modelPoint->balance;
                $modelDoc->total_point = $modelPoint->balance + $modelDoc->total_debit_sales_point;
                $modelDoc->save();
            }*/

        }

        return $ok;
    }

    public function getUserList($user_id = null)
    {
        if($user_id == null) {
            return ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username');
        } else {
            return ArrayHelper::map(User::find()->where(['<>', 'id', $user_id])->asArray()->all(), 'id', 'username');
        }
    }
}
