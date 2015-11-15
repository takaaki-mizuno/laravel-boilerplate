<?php namespace App\Repositories;

interface AdminUserRoleRepositoryInterface extends CompositeKeyModelRepositoryInterface
{

    /**
     * @param  int $id
     * @return boolean
     */
    public function deleteByAdminUserId($id);
}