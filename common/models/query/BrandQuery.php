<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Brand]].
 *
 * @see \common\models\Brand
 */
class BrandQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Brand[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Brand|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
