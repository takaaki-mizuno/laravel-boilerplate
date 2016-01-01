<?php namespace App\Repositories;

interface AdminUserRoleRepositoryInterface extends SingleKeyModelRepositoryInterface
{

    /**
     * @param  int $id
     * @return boolean
     */
    public function deleteByAdminUserId($id);

    /**
     * @param  int $adminUserId
     * @param  array $roles
     */
    public function setAdminUserRoles($adminUserId, $roles);
}