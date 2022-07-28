<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Creating Permissions : \n list candidates asdfdsf \n list deleted candidates \n delete candidates\n");
        $list_candidates = Permission::create(['name' => 'list candidates']);
        $list_deleted_candidates = Permission::create(['name' => 'list deleted candidates']);
        $delete_candidates = Permission::create(['name' => 'delete candidates']);


        $this->command->info("Creating Roles : \n admin \n staff\n");
        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->givePermissionTo($list_candidates);
        $admin_role->givePermissionTo($list_deleted_candidates);
        $admin_role->givePermissionTo($delete_candidates);

        $staff_role = Role::create(['name' => 'staff']);
        $staff_role->givePermissionTo($list_candidates);

        $this->command->info("Creating Default Admin: \n Email : <comment>admin@gmail.com</comment> \n Password : <comment>abcd1234</comment>\n");
        $admin = User::create([
            'name' => "Test Admin",
            'email' => "admin@gmail.com",
            'password' => Hash::make('abcd1234')
        ]);

        $admin->assignRole('admin');

        $this->command->info("Creating Default Staff Member: \n Email : <comment>staff@gmail.com</comment> \n Password : <comment>abcd1234</comment>\n");
        $admin = User::create([
            'name' => "Test Staff",
            'email' => "staff@gmail.com",
            'password' => Hash::make('abcd1234')
        ]);

        $admin->assignRole('staff');

        $this->call(SkillSeeder::class);

        $this->call(LocationSeeder::class);
    }
}
