<?php
namespace app\models;

use omgdef\multilingual\MultilingualQuery;

/**
 * Class AuthItemQuery
 * @package app\models
 */
class AuthItemQuery extends MultilingualQuery
{
    /**
     * @return AuthItemQuery
     */
    public function roles()
    {
        return $this->andWhere(['type' => AuthItem::AUTH_ITEM_ROLE]);
    }
}
