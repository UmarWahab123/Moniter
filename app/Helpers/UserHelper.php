<?php

namespace App\Helpers;

use App\Role;
use App\User;
use Illuminate\Support\Str;
use App\Mail\UserSignupMail;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserHelper
{
    public static function index($request)
    {
        if ($request->ajax()) {
            return (new UserHelper)->UserDatatable();
        }
        return view('admin.users.index');
    }
    private function UserDatatable()
    {
        $query = User::with('userWebsites');
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '
                        <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-user"  title="Edit"><i class="fa fa-pencil"></i></button>
                     ';
                if ($item->status == 1) {
                    $html_string .= '<button class="btn btn-sm btn-outline-danger user-status" data-status=0 value="' . $item->id . '"   title="Suspend User"><i class="fa fa-ban"></i></button>';
                } else if ($item->status == 0) {
                    $html_string .= '<button class="btn btn-sm btn-outline-success user-status" data-status=1 value="' . $item->id . '"   title="Activate User"  ><i class="fa fa-check-circle"></i></button>';
                }
                return $html_string;
            })
            ->addColumn('counter', function ($item) {
                if ($item->userWebsites->isEmpty()) {
                    return 0;
                }
                return $item->userWebsites->count('id');
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge badge-success ">Active</span>';
                } else {
                    return '<span class="badge badge-danger ">Suspended</span>';
                }
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
    public static function store($request)
    {
        if (!empty($request->all())) {
            $mailData = $request->all();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt(12345678);
            $user->status = 1;
            if ($user->save()) {
                $userRole = Role::where('name', 'user')->first();
                $user->roles()->attach($userRole);
                Mail::to($request->email)->send(new UserSignupMail($mailData));
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }
    }
    public static function userStatus($request)
    {
        $user = User::where('id', $request->id)->update(['status' => $request->status]);
        return ($user == 1) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }
    public static function update($request)
    {
        if (!empty($request->all())) {
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            return ($user->save()) ? response()->json(['success' => true]) : response()->json(['success' => false]);
        }
    }
    public static function resendEmail($request)
    {
        $user = User::find(Auth::user()->id);
        return $user->resendEmail();
    }
    public static function sendVerificationCodeEmail($request)
    {
        $user = User::where('email', $request->email)->where('status', 1)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $user->verification_code = Str::random(128);
            $user->save();
            $user->sendVerificationCodeEmail($user->verification_code);
            return response()->json(['success' => true]);
        }
    }
}
