<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('role_id', 3)->first();
        if ($user)
        {
            $user->delete();
        }
        $sql = file_get_contents(database_path() . '/seeds/superAdminSeeder.sql');
        DB::statement($sql);
    }
}
