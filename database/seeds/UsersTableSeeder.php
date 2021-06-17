<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'     => 'Admin',
            'username' => 'admin',
            'email'    => 'info@ogene983fm.com',
            'password' => Hash::make('ImUtU*dd#03Y93'),
        ]);

        $role_r = Role::where('name', '=', 'admin')->firstOrFail();
        $user->assignRole($role_r); //Assigning role to user
    }
}
