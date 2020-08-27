<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'email' => 'support@pkteam.com',
                'password' =>  Hash::make('PkTeam12453!@$%#'),
                'created_at'=>date('y-m-d H:i:s'),
                'updated_at'=>date('y-m-d H:i:s'),
            ),
        ));
    }
}
