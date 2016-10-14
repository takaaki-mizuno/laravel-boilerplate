<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AdminUserRoleRepositoryInterface;
use App\Models\AdminUserRole;

class AdminUserRoleRepository extends SingleKeyModelRepository implements AdminUserRoleRepositoryInterface
{
    public function getBlankModel()
    {
        return new AdminUserRole();
    }

    public function rules()
    {
        return [
        ];
    }

    public function create($input)
    {
        $role = array_get($input, 'role', '');
        if (!array_key_exists($role, config('admin_user.roles', []))) {
            return;
        }

        return parent::create($input);
    }

    public function deleteByAdminUserId($id)
    {
        $modelClass = $this->getModelClassName();
        $modelClass::where('admin_user_id', $id)->delete();

        return true;
    }

    public function setAdminUserRoles($adminUserId, $roles)
    {
        $this->deleteByAdminUserId($adminUserId);
        foreach ($roles as $role) {
            $this->create([
                'admin_user_id' => $adminUserId,
                'role' => $role,
            ]);
        }
    }
}
