<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property int $id
 * @property string $company
 * @property string|null $payment_tnc
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_by
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User[] $users
 */
class Company extends \yii\db\ActiveRecord
{
    public $imageFile;
    public $importFile;

    const SCENARIO_IMPORT = 'import';
    const SCENARIO_EXPORT = 'import';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company}}';
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
            [['company'], 'required'],
            [['imageFile'], 'image', 'extensions' => 'png, jpg, jpeg'/*, 'maxSize' => 10 * 1024 * 1024*/], //10mb
            [['payment_tnc'], 'string'],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['company', 'image'], 'string', 'max' => 255],
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
            'image' => 'Letterhead',
            'imageFile' => 'Choose Letterhead',
            'payment_tnc' => 'Payment Tnc',
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
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['company' => 'id']);
    }

    /**
     * Gets company list
     * 
     * @param int $id
     * 
     * @return array
     */
    public function getCompanyList()
    {
        return ArrayHelper::map(Company::find()->all(), 'id', 'company');
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CompanyQuery(get_called_class());
    }

    public function save($runValidation = true, $attributeNames = null) 
    {
        if($this->imageFile) {
            //$this->image =  Yii::getAlias('/products/'.Yii::$app->security->generateRandomString(32).'/'.$this->imageFile->name);
            $this->image =  Yii::getAlias('/letterheads/'.$this->imageFile->name);
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
    }

    public function getImageUrl() 
    {
        if(!$this->image) {
            return Yii::$app->params['backendUrl'].'/img/noimg.jpeg';
        }
        return Yii::$app->params['backendUrl'].'/storage'.$this->image;
    }

}
