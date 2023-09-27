<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $brand_name
 * @property string|null $type
 * @property int|null $code
 * @property string|null $image
 * @property float $retail_base_price
 * @property int|null $project_currency
 * @property float|null $project_base_price
 * @property float|null $threshold_discount
 * @property float|null $standard_costing
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property Currency $projectCurrency
 * @property User $updatedBy
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFile;
    public $importFile;
    public $quantity = 1;
    public $discount = 0.00;
    public $discount2 = 0.00;
    public $discountrm = 0.00;

    const SCENARIO_IMPORT = 'import';
    const SCENARIO_EXPORT = 'import';

    const NORMAL = 'normal';
    const SERVICE_PACKAGE = 'service_package';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /*public function scenarios()
    {
        return [
            self::SCENARIO_IMPORT=>['importFile']
        ];
    }*/

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
            /** IMPORT DATA */
            //[[ 'importFile'], 'required', 'on'=>self::SCENARIO_IMPORT, 'message'=>'{attribute} Please upload file' ],
            //[['exportFile'],'required','on'=>self::SCENARIO_EXPORT],
            [['name', /*'agent_comm', 'retail_base_price',*/ /*'type',*/ 'code', 'description', /*'retail_base_price', */'project_currency', /*'project_base_price', 'threshold_discount', 'project_threshold_discount', 'admin_discount', 'standard_costing',*/ 'product_type'], 'required'],
            [['imageFile'], 'image', 'extensions' => 'png, jpg, jpeg'/*, 'maxSize' => 10 * 1024 * 1024*/], //10mb
            [['project_currency', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['retail_base_price', 'project_base_price', 'threshold_discount', 'project_threshold_discount', 'admin_discount', 'standard_costing'], 'number'],
            [['name', 'brand_name', /*'type',*/ 'image'], 'string', 'max' => 255],
            [['quantity'], 'default', 'value' => 1],
            [['agent_comm','retail_base_price','project_currency','project_base_price','threshold_discount','project_threshold_discount','admin_discount','standard_costing','discount'], 'default', 'value' => 0],
            [['discount2'], 'default', 'value' => 0.00],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['project_currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['project_currency' => 'id']],
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
            'name' => 'NAME',
            'brand_name' => 'BRAND',
            'type' => 'Type',
            'code' => 'PRODUCT CODE',
            'image' => 'PICTURE',
            'imageFile' => 'Choose Image',
            'agent_comm' => 'Agent Commission',
            'retail_base_price' => 'Retail Base Price',
            'project_currency' => 'Project Currency',
            'project_base_price' => 'Project Base Price',
            'threshold_discount' => 'Threshold Discount',
            'project_threshold_discount' => 'Project Threshold Discount',
            'admin_discount' => 'Admin Discount',
            'standard_costing' => 'Standard Costing',
            'discount' => 'Disc.',
            'discount2' => 'Admin Disc.',
            'description' => 'Description',
            'status' => 'Published',
            'importFile' => 'Import File',
            'exportFile' => 'Export File',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
     * Gets query for [[ProjectCurrency]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CurrencyQuery
     */
    public function getProjectCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'project_currency']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TypeQuery
     */
    public function getType()
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
        return $this->hasOne(Brand::className(), ['brand' => 'brand']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProductQuery(get_called_class());
    }

    public function save($runValidation = true, $attributeNames = null) 
    {
        if(!$this->isNewRecord) {
            if($this->imageFile) {
                //$this->image =  Yii::getAlias('/products/'.Yii::$app->security->generateRandomString(32).'/'.$this->imageFile->name);
                $this->image =  Yii::getAlias('/products/'.$this->imageFile->name);
            }

            $transaction = Yii::$app->db->beginTransaction();
            $ok = parent::save($runValidation, $attributeNames);

            if($ok && $this->imageFile) {
                $fullPath = Yii::getAlias('@backend/web/storage'.$this->image);
                $dir = dirname($fullPath);
                if(!FileHelper::createDirectory($dir) | !$this->imageFile->saveAs($fullPath)) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $transaction->commit();
            return $ok;

        } else {
            return parent::save($runValidation, $attributeNames);
        }
    }

    public function getImageUrl() 
    {
        if(!$this->image) {
            return Yii::$app->params['backendUrl'].'/img/noimg.jpeg';
        }
        return Yii::$app->params['backendUrl'].'/storage'.$this->image.'?v='.time();
    }

    public function getCurrencyList()
    {
        return ArrayHelper::map(Currency::find()->asArray()->all(), 'id', 'currency');
    }

    public function getTypeList()
    {
        return ArrayHelper::map(Type::find()->asArray()->all(), 'id', 'type');
    }

    public function getProductTypeList() {

        return [
            self::NORMAL => 'Normal', 
            self::SERVICE_PACKAGE => 'Service Package',
        ];
    }
}
