<?php

use Illuminate\Database\Seeder;
use App\Models\AdminUserRole;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        /** @var \App\Repositories\AdminUserRepositoryInterface $adminUserRepository */
        $adminUserRepository = \App::make('App\Repositories\AdminUserRepositoryInterface');
        /** @var \App\Repositories\AdminUserRoleRepositoryInterface $adminUserRoleRepository */
        $adminUserRoleRepository = \App::make('App\Repositories\AdminUserRoleRepositoryInterface');

        /** @var \App\Models\AdminUser $adminUser */
        $adminUser = $adminUserRepository->create([
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => 'test',
        ]);

        $adminUserRoleRepository->create([
            'admin_user_id' => $adminUser->id,
            'role' => AdminUserRole::ROLE_SUPER_USER,
        ]);
    }
}
