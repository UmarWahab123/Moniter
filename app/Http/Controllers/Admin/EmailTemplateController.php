<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\EmailTemplateHelper;
use App\TemplateKeyword;

class EmailTemplateController extends Controller
{
    public function index()
    {
        return view('admin.email_templates.index');
    }

    public function getTemplatesData(Request $request)
    {
        return EmailTemplateHelper::getTemplatesData($request);
    }

    public function create()
    {
        $template_keywords = TemplateKeyword::select('keyword')->where('status', 1)->get();
        return view('admin.email_templates.create', compact('template_keywords'));
    }

    public function store(Request $request)
    {
        return EmailTemplateHelper::store($request);
    }

    public function edit($id)
    {
        $template = EmailTemplate::find($id);
        $template_keywords = TemplateKeyword::select('keyword')->where('status', 1)->get();
        return view('admin.email_templates.edit', compact('template', 'template_keywords'));
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
