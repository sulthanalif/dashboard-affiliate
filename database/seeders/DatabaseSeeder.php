<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Role::create(['name' => 'Admin']);
        // $users = User::factory(15)->create();
        // $roleAdmin = Role::where('name', 'Admin')->first();
        // foreach ($users as $user) {
        //     $user->assignRole($roleAdmin);
        // }


        //role permission
        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleAdmin = Role::create(['name' => 'Admin']);

        $permissions = [
            'dashboard-page',
            'options',
            'master-data',
            'user-page',
            'user-create',
            'user-edit',
            'user-delete',
            'affiliate-page',
            'affiliate-create',
            'affiliate-edit',
            'affiliate-delete',
            'bank-page',
            'bank-create',
            'bank-edit',
            'bank-delete',
            'log-page',
            'log-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roleSuperAdmin->givePermissionTo(Permission::all());
        $roleAdmin->givePermissionTo([
            'dashboard-page',
            'master-data',
            'affiliate-page',
            'affiliate-create',
            'affiliate-edit',
            'bank-page',
            'bank-create',
            'bank-edit',
            'bank-delete',
        ]);

        $superAdmin = User::factory()->create([
            'name' => 'IT Bervin',
            'email' => 'it@bervin.co.id',
            'password' => Hash::make('An9gr3k!!'),
        ]);

        $superAdmin->assignRole($roleSuperAdmin);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@bervin.co.id',
            'password' => Hash::make('B3rvin123'),
        ]);

        $admin->assignRole($roleAdmin);


        $this->call(BankSeeder::class);
    }
}
