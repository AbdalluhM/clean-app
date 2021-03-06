<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'category-list',
           'category-create',
           'category-edit',
           'category-delete',
           'service-list',
           'service-create',
           'service-edit',
           'service-delete',
           'recieve-list',
           'recieve-create',
           'recieve-edit',
           'recieve-delete',
           'slider-list',
           'slider-create',
           'slider-edit',
           'slider-delete',
           'offer-list',
           'offer-create',
           'offer-edit',
           'offer-delete',
           'size-list',
           'size-create',
           'size-edit',
           'size-delete',

        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission,'guard_name'=>'admin']);
        }
    }
}
