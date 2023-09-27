<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\PointDoc]].
 *
 * @see \common\models\PointDoc
 */
class PointDocQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\PointDoc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\PointDoc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
