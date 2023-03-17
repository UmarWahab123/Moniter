<?php

namespace App\Helpers;

use App\Models\OperatingSystem;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class OperatingSystemHelper
{
    //Permission Start from here
    public static function operatingSystem($request)
    {
        if ($request->ajax()) {
            return (new OperatingSystemHelper)->OperatingSystemDatatable();
        }
        return view('superAdmin.operating_system.index');
    }
    private function OperatingSystemDatatable()
    {
        $query = OperatingSystem::all();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '
                <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit_operating_system"  title="Edit"><i class="fa fa-pencil"></i></button>
                <button  value="' . $item->id . '" class="btn btn-sm btn-outline-danger btn_delete ml-1" title="Delete"><i class="fa fa-trash"></i></button>
                '
                ;
             return $html_string;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public static function store($request)
    {
        $id = $request['id'];
        $data = $request;
        $action = "Added";
        if ($id) {
            $action = "Updated";
            $operatingSystem = OperatingSystem::find($id)->update($data);
        } else {
            $operatingSystem = OperatingSystem::create($data);
        }
        $operatingSystem = [$operatingSystem, $action];
        return $operatingSystem; 
    }
    public static function EditOperatingSystem($request)
    {
        $operatingSystem = OperatingSystem::where('id', $request->id)->first();
        if ($operatingSystem != null) {
            return response()->json(['success' => true, 'data' => $operatingSystem]);
        }
        return response()->json(['success' => false]);
    }
    public static function delete($request)
    {
         $operatingSystem = OperatingSystem::find($request->id)->delete();
         return response()->json(['success' => true]);
    }
}
