<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%point}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property float|null $balance
 * @property int|null $status
 * @property int|null $created_at
 *
 * @property User $user
 */
class Point extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%point}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at'], 'integer'],
            [['balance'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'balance' => 'Balance',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PointQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PointQuery(get_called_class());
    }
}
