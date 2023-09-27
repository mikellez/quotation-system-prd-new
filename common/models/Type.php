<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%type}}".
 *
 * @property int $id
 * @property string $type
 *
 * @property Products[] $products
 */
class Type extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductsQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id', 'type' => 2]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TypeQuery(get_called_class());
    }
}
