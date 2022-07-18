<?php

namespace Tests\Unit;

use App\Http\Controllers\SuperAdmin\UserController;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Request;

class SuperAdminTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::where('role_id', 3)->first();
        $this->actingAs($user);
    }
    public function test_Delete_Admin_User_with_His_Sub_Users()
    {
        $request = (new Request)->replace([
            'id' => '143'
        ]);
        $response = (new UserController)->delete($request);

        $user = User::where('id', $request->id)->first();
        $user != null ? $this->assertEquals($user->is_deleted, 1) : $this->assertTrue(false);
    }
}
