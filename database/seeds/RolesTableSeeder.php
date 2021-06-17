<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{

    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Seed the default permissions
        $permissions = $this->defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        // create permissions
        Permission::create(['name' => 'edit discussion']);
        Permission::create(['name' => 'delete discussion']);
        Permission::create(['name' => 'publish discussion']);
        Permission::create(['name' => 'unpublish discussion']);



        $role = Role::create(['name' => 'super admin']);
        $role->givePermissionTo(Permission::all());

        Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'presenter']);
        $role->givePermissionTo([
            'edit discussion', 'delete discussion', 'publish discussion', 'unpublish discussion'
        ]);

        $role = Role::create(['name' => 'fan']);



    }
}
