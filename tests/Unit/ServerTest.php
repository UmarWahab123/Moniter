<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\ServerController;
use App\Monitor;
use App\Server;
use App\User;

class ServerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::first();
        $this->actingAs($user);
    }
    public function test_Save_Server()
    {
        $request = (new Request)->replace([
            "name" => "PkTeam",
            "ip_address" => "192.16.100.100",
            "operating_system" => "linux"
        ]);
        $response = (new ServerController)->addServer($request);

        $server = Server::where('ip_address', $request->ip_address)->first();
        $server != null ? $this->assertEquals($server->ip_address, $request->ip_address) : $this->assertTrue(false);
    }
    public function test_Edit_Server()
    {
        $request = (new Request)->replace([
            'id' => '8',
            'name' => 'PkTeam',
            'operating_system' => 'linux'
        ]);
        $response = (new ServerController)->update($request);

        $server = Server::find($request->id);
        $server != null ? $this->assertEquals($server->id, $request->id) : $this->assertTrue(false);
    }
    public function test_Delete_Server()
    {
        $request = (new Request)->replace([
            'id' => '8'
        ]);
        $response = (new ServerController)->destroy($request);

        $server = Server::find($request->id);
        $server == null ? $this->assertTrue(true) : $this->assertTrue(false);
    }
    public function test_Bind_Website_to_Server()
    {
        $request = (new Request)->replace([
            'website_id' => '131',
            'server_id' => '4'
        ]);
        $response = (new ServerController)->saveBindedWebsites($request);

        $monitor = Monitor::find($request->website_id);
        $monitor != null ? $this->assertEquals($request->server_id, $monitor->server_id) : $this->assertTrue(false);
    }
}
