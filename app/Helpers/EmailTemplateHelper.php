<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\EmailTemplate;
use App\TemplateKeyword;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class EmailTemplateHelper
{
    public static function getTemplatesData($request)
    {
        $query = EmailTemplate::with('user')->where('status', 1)->latest();
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '<div class="icons">' . '
                              <a href="' . route('templates.edit', ['id' => $item->id]) . '" class="btn btn-outline-success btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                              <a href="javascript:;" data-url="' . route('templates.delete', ['id' => $item->id]) . '" class="btn btn-outline-danger btn-sm btn-delete" title="Delete"><i class="fa fa-trash"></i></a>
                          </div>';
                return $html_string;
            })
            ->addColumn('user', function ($item) {
                return $item->user_id != null ? $item->user->name : '--';
            })
            ->addColumn('created_at', function ($item) {
                return $item->created_at != null ?  Carbon::parse(@$item->created_at)->format('d/m/Y') : '--';
            })
            ->addColumn('updated_at', function ($item) {
                return $item->updated_at != null ?  Carbon::parse(@$item->updated_at)->format('d/m/Y') : '--';
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public static function store($request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);
        $template = new EmailTemplate();
        $template->name = $request->name;
        $template->subject = $request->subject;
        $template->body = $request->body;
        $template->user_id = Auth::user()->id;
        $template->save();
        return response()->json(['success' => true]);
    }

    public static function update($request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        $template = EmailTemplate::find($request->id);
        $template->subject = $request->subject;
        $template->body = $request->body;
        $template->user_id = Auth::user()->id;
        $template->save();
        return response()->json(['success' => true]);
    }

    public static function delete($id)
    {
        $template = EmailTemplate::find($id);
        $template->status = 0;
        $template->save();
        return response()->json(['success' => true]);
    }

    public static function storeKeyword($request)
    {
        $template_keyword = TemplateKeyword::where('keyword', $request->keyword)->first();
        if ($template_keyword) {
            return response()->json(['success' => false]);
        }
        $template_keyword = new TemplateKeyword();
        $template_keyword->keyword = $request->keyword;
        $template_keyword->save();
        return response()->json(['success' => true, 'keyword' => $template_keyword->keyword]);
    }
}
