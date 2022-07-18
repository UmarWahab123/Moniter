<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\WebsiteController;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Monitor;
use App\User;
use App\UserWebsite;
use App\UserWebsitePermission;

class WebsiteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::first();
        $this->actingAs($user);
    }
    public function test_Save_Website()
    {
        $request = (new Request)->replace([
            "url" => "https://schaindev.d11u.com/admin/roles",
            "title" => "Schain",
            "emails" => "akif@knowpakistan.net",
            "developer_email" => "akif@knowpakistan.net",
            "owner_email" => "akif@knowpakistan.net",
            "server" => "4",
            "ssl" => "on"
        ]);
        $response = (new WebsiteController)->store($request);

        $monitor = Monitor::where('url', $request->url)->first();
        $monitor != null ? $this->assertEquals($monitor->url, $request->url) : $this->assertTrue(false);
    }
    public function test_Edit_Website()
    {
        $request = (new Request)->replace([
            'id' => '144',
            "title" => "Schain",
            "emails" => "akif@knowpakistan.net",
            "developer_email" => "akif@knowpakistan.net",
            "owner_email" => "akif@knowpakistan.net",
            "server" => "4",
            "ssl" => "on"
        ]);
        $response = (new WebsiteController)->update($request);

        $monitor = Monitor::find($request->id);
        $monitor != null ? $this->assertEquals($monitor->id, $request->id) : $this->assertTrue(false);
    }
    public function test_Feature_Website()
    {
        $request = (new Request)->replace([
            'id' => '144',
            "status" => "1"
        ]);
        $response = (new WebsiteController)->featureWebsite($request);

        $monitor = UserWebsite::where('website_id', $request->id)->first();
        $monitor != null ? $this->assertEquals((int)$monitor->is_featured, (int)$request->status) : $this->assertTrue(false);
    }
    public function test_Delete_Website()
    {
        $request = (new Request)->replace([
            'id' => '144'
        ]);
        $response = (new WebsiteController)->destroy($request);

        $monitor = Monitor::find($request->id);
        $monitor == null ? $this->assertTrue(true) : $this->assertTrue(false);
    }
    public function test_Assign_Websites_To_User()
    {
        $request = (new Request)->replace([
            "selected_items" => [
                "all_checkboxes",
                "131",
                "140"
            ],
            "sub_user_id" => "140",
            "permission" => "1"
        ]);
        $response = (new WebsiteController)->assignWebsiteToSubUser($request);

        $data = UserWebsitePermission::where('user_id', $request->user_id)->whereIn('website_id', $request->selected_items)->get();
        $data != null ? $this->assertTrue(true) : $this->assertTrue(false);
    }
}
