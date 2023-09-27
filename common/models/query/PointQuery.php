<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Point]].
 *
 * @see \common\models\Point
 */
class PointQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Point[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Point|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
