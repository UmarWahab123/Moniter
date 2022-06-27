<?php

namespace App\Http\Controllers\User;

use App\EmailTemplate;
use App\TemplateKeyword;
use Illuminate\Http\Request;
use App\Helpers\EmailTemplateHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        if (Auth::user()) {
            $user_permissions = unserialize(Auth::user()->permissions);
            View::share(['user_permissions' => $user_permissions]);
        }
    }

    public function index()
    {
        return view('user.email_templates.index');
    }

    public function getTemplatesData(Request $request)
    {
        return EmailTemplateHelper::getTemplatesData($request);
    }

    public function create()
    {
        $template_keywords = TemplateKeyword::select('keyword')->where('status', 1)->get();
        return view('user.email_templates.create', compact('template_keywords'));
    }

    public function store(Request $request)
    {
        return EmailTemplateHelper::store($request);
    }

    public function edit($id)
    {
        $template = EmailTemplate::find($id);
        $template_keywords = TemplateKeyword::select('keyword')->where('status', 1)->get();
        return view('user.email_templates.edit', compact('template', 'template_keywords'));
    }

    public function update(Request $request)
    {
        return EmailTemplateHelper::update($request);
    }

    public function delete($id)
    {
        return EmailTemplateHelper::delete($id);
    }

    public function storeKeyword(Request $request)
    {
        return EmailTemplateHelper::storeKeyword($request);
    }
}
