<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Currencies]].
 *
 * @see \common\models\Currencies
 */
class CurrencyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Currencies[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Currencies|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
