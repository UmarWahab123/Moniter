<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\User;
use App\Role;
use Hash;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query=User::get();
        if($request->ajax())
        {

            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '
                          <button  value="'.$item->id.'"  class="btn btn-primary btn-sm edit-user"  title="Edit"><i class="fa fa-pencil"></i></button>                          
                     ';
                     if($item->status==1)
                     {
                        $html_string .= ' <button   class="btn btn-sm btn-danger user-status" data-status=0 value="'.$item->id.'"   title="Suspend User"><i class="fa fa-ban"></i></button>';
                     }
                     else if($item->status==0)
                     {
                        $html_string .= ' <button  class="btn btn-sm btn-success user-status" data-status=1 value="'.$item->id.'"   title="Activate User"><i class="fa fa-check-circle"></i></button>';
                     }
                return $html_string;
            })
            ->addColumn('status', function ($item) {
                if($item->status==1)
                {
                    return '<span class="badge badge-success ">Active</span>';
                }
                else
                {
                    return '<span class="badge badge-danger ">Suspended</span>';
                }
             })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
        return view('admin.users.index');
        
    }
    public function store(Request $request)
    {
        
        if(!empty($request->all()))
        {
            $user=new User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->status=1;
            if($user->save())
            {
                $userRole=Role::where('name','user')->first();
                $user->roles()->attach($userRole);
                return response()->json(['success'=>true]);
            }
            else
            {
                return response()->json(['success'=>false]);
            }
        }
    }
    public function userStatus(Request $request)
    {
        $user=User::where('id',$request->id)->update(['status'=>$request->status]);
        if($user==1)
        {
            return response()->json(['success'=>true]);
        }
        else
        {
            return response()->json(['success'=>false]);
        }
    }
    public function update(Request $request)
    {
        if(!empty($request->all()))
        {
            $user=User::find($request->id);
            $user->name=$request->name;
            $user->email=$request->email;
            if($user->save())
            {
                return response()->json(['success'=>true]);
            }
            else
            {
                return response()->json(['success'=>false]);
            }
        }
    }
}
