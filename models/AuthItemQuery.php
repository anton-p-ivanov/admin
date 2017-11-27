<?php
namespace app\models;

use yii\db\ActiveQuery;

/**
 * Class AuthItemQuery
 * @package app\models
 */
class AuthItemQuery extends ActiveQuery
{
    /**
     * @return AuthItemQuery
     */
    public function roles()
    {
        return $this->andWhere(['type' => AuthItem::AUTH_ITEM_ROLE]);
    }
}
