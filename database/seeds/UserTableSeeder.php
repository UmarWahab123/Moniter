<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $adminRole=Role::where('name','admin')->first();
        $userRole=Role::where('name','user')->first();
        $admin=User::create([
            'name'=>'Admin',
            'email'=>'support@pkteam.com',
            'password'=>Hash::make('PkTeam12453!@$%#'),
        ]);
        $user=User::create([
            'name'=>'User',
            'email'=>'user@pkteam.com',
            'password'=>Hash::make(12345678)
        ]);
        DB::table('role_user')->delete();
        $admin->roles()->attach($adminRole);
        $user->roles()->attach($userRole);

    }
}
