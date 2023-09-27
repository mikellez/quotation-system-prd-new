<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%auth_assignment}}".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int|null $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    const TYPE_USER_ROLE = 2;

    public $role;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id', 'role'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'role' => 'Role',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ItemName]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AuthItemQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UsersQuery
     */
    public function getUser()
    {
        return $this->hasMany(Users::className(), ['user_id' => 'id']);
    }

    /**
     * Gets user role list
     * 
     * @param int $id
     * 
     * @return array
     */
    public function getUserRoleList()
    {
        $authAssignmentModel = AuthAssignment::findOne(['user_id'=>Yii::$app->user->id]);
        if($authAssignmentModel->item_name=="admin") {
            return ArrayHelper::map(AuthItem::find()
            ->where(['type' => self::TYPE_USER_ROLE])
            ->andWhere(['<>', 'name', 'accountant'])
            ->asArray()->all(), 'name', 'description');
        } else if($authAssignmentModel->item_name=="officer"){
            return ArrayHelper::map(AuthItem::find()
            ->where(['type' => self::TYPE_USER_ROLE])
            ->andWhere(['<>', 'name', 'accountant'])
            ->andWhere(['<>', 'name', 'admin'])
            ->asArray()->all(), 'name', 'description');
        }

        return ArrayHelper::map(AuthItem::find()->where(['type' => self::TYPE_USER_ROLE])->asArray()->all(), 'name', 'description');
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AuthAssignmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AuthAssignmentQuery(get_called_class());
    }
}
