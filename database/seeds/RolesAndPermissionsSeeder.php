<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'book.read']);
        Permission::create(['name' => 'book.write']);
        Permission::create(['name' => 'mark.math']);
        Permission::create(['name' => 'mark.cs']);
        Permission::create(['name' => 'mark.cr']);
        Permission::create(['name' => 'user']);

        // create roles and assign created permissions

        $role = Role::create(['name' => '上传案例']);
        $role->givePermissionTo(['book.read', 'book.write']);

        $role = Role::create(['name' => '标记数学']);
        $role->givePermissionTo(['book.read', 'mark.math']);
        $role = Role::create(['name' => '标记计算机']);
        $role->givePermissionTo('mark.cs');
        $role = Role::create(['name' => '标记批判性思维']);
        $role->givePermissionTo('mark.cr');

        $role = Role::create(['name' => '管理员']);
        $role->givePermissionTo(Permission::all());
    }
}
