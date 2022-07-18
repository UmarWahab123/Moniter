<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\UserController;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Request;

class UserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::first();
        $this->actingAs($user);
    }
    public function test_Save_User()
    {
        $request = (new Request)->replace([
            'name' => 'Husnain khan',
            'email' => 'abc@knowpakistan.org'
        ]);
        $response = (new UserController)->store($request);

        $user = User::where('email', $request->email)->first();
        $user != null ? $this->assertEquals($user->email, $request->email) : $this->assertTrue(false);
    }
    public function test_Edit_User()
    {
        $request = (new Request)->replace([
            'id' => '143',
            'name' => 'Husnain khan',
            'email' => 'abc@knowpakistan.org'
        ]);
        $response = (new UserController)->update($request);

        $user = User::where('email', $request->email)->first();
        $user != null ? $this->assertEquals($user->email, $request->email) : $this->assertTrue(false);
    }
    public function test_Suspend_User()
    {
        $request = (new Request)->replace([
            'id' => '143',
            'status' => '0'
        ]);
        $response = (new UserController)->userStatus($request);

        $user = User::find($request->id);
        $user != null ? $this->assertEquals($user->status, $request->status) : $this->assertTrue(false);
    }
    public function test_UnSuspend_User()
    {
        $request = (new Request)->replace([
            'id' => '143',
            'status' => '1'
        ]);
        $response = (new UserController)->userStatus($request);

        $user = User::find($request->id);
        $user != null ? $this->assertEquals($user->status, $request->status) : $this->assertTrue(false);
    }
}
