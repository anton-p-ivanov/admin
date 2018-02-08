<?php

namespace users\components\traits;

use users\models\UserAccount;
use users\models\UserRole;
use users\models\UserSite;

/**
 * Trait Duplicator
 *
 * @package users\components\traits
 */
trait Duplicator
{
    /**
     * @param UserAccount $account
     * @param string $uuid
     * @return bool
     */
    protected function duplicateAccount(UserAccount $account, $uuid)
    {
        $account->user_uuid = $uuid;
        $clone = $account->duplicate();

        return $clone->save();
    }

    /**
     * @param UserRole $role
     * @param string $uuid
     * @return bool
     */
    protected function duplicateRole(UserRole $role, $uuid)
    {
        $role->user_id = $uuid;
        $clone = $role->duplicate();

        return $clone->save();
    }

    /**
     * @param UserSite $site
     * @param string $uuid
     * @return bool
     */
    protected function duplicateSite(UserSite $site, $uuid)
    {
        $site->user_uuid = $uuid;
        $clone = $site->duplicate();

        return $clone->save();
    }
}