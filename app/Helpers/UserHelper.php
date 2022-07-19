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
        if (Auth::user()->role_id == 1) {
            return view('admin.users.index');
        }
        return view('user.users.index');
    }
    public static function UserDatatable()
    {
        $query = User::with('userWebsites');
        if (Auth::user()->role_id == 1) {
            $query = $query->where('parent_id', Auth::user()->id);
        } else if (Auth::user()->role_id == 3) {
            $query = $query->where('role_id', 1);
        }
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                if ($item->is_deleted == 1) {
                    return '--';
                }
                $html_string = "";
                if (Auth::user()->role_id == 1) {
                    $html_string .= '
                            <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-user"  title="Edit"><i class="fa fa-pencil"></i></button>
                         ';
                }
                if ($item->status == 1) {
                    $html_string .= '<button class="btn btn-sm btn-outline-danger user-status" data-status=0 value="' . $item->id . '"   title="Suspend User"><i class="fa fa-ban"></i></button>';
                } else if ($item->status == 0) {
                    $html_string .= '<button class="btn btn-sm btn-outline-success user-status" data-status=1 value="' . $item->id . '"   title="Activate User"  ><i class="fa fa-check-circle"></i></button>';
                }
                if (Auth::user()->role_id == 3) {
                    $html_string .= '<button class="btn btn-sm btn-outline-danger btn_delete" value="' . $item->id . '"   title="Delete User"  ><i class="fa fa-trash"></i></button>';
                }
                return $html_string;
            })
            ->addColumn('name', function ($item) {
                if (Auth::user()->role_id == 3) {
                    return $item->name;
                }
                $route = route('users.permissions', $item->id);
                if (Auth::user()->role_id == 2) {
                    $route = route('users.users-permissions', $item->id);
                }
                $html_string = '
                        <a href="' . $route . '" title="User Details">' . $item->name . '</a>
                     ';
                return $html_string;
            })
            ->addColumn('counter', function ($item) {
                if ($item->userWebsites->isEmpty()) {
                    return 0;
                }
                return $item->userWebsites->count('id');
            })
            ->addColumn('status', function ($item) {
                if ($item->is_deleted == 1) {
                    return '<span class="badge badge-danger ">Deleted</span>';
                } else if ($item->status == 1) {
                    return '<span class="badge badge-success ">Active</span>';
                } else {
                    return '<span class="badge badge-danger ">Suspended</span>';
                }
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action', 'status', 'name'])
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
            $user->role_id = 2;
            $user->parent_id = Auth::user()->id;
            if ($user->save()) {
                Mail::to($request->email)->send(new UserSignupMail($mailData));
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }
    }
    public static function userStatus($request)
    {
        $user = User::find($request->id);
        $user->status = $request->status;
        $user->save();
        if (Auth::user()->role_id == 3) {
            if ($user->parent_id == null) {
                $users = User::where('parent_id', $user->id)->get();
                foreach ($users as $sub_users) {
                    $sub_users->status = $user->status;
                    $sub_users->save();
                }
            }
        }
        return ($user) ? response()->json(['success' => true]) : response()->json(['success' => false]);
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
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->is_deleted == 1) {
                return response()->json(['deleted_user' => true]);
            } else if ($user->status == 0) {
                return response()->json(['suspended_user' => true]);
            } else if ($user->remember_token != null) {
                return response()->json(['direct_login' => true]);
            }
            $user->verification_code = Str::random(128);
            $user->save();
            $user->sendVerificationCodeEmail($user->verification_code);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public static function userPermissions($user_id)
    {
        $user = User::find($user_id);
        $permissions = unserialize($user->permissions);
        if (Auth::user()->role_id == 1) {
            return view('admin.users.permissions', compact('permissions', 'user'));
        }
        return view('user.users.permissions', compact('permissions', 'user'));
    }
    public static function saveUserPermissions($request)
    {
        $user = User::find($request->id);
        $permissions = [];
        if ($request->dashboard == 1) {
            array_push($permissions, 'dashboard');
        }
        if ($request->websites == 1) {
            array_push($permissions, 'websites');
        }
        if ($request->email_templates == 1) {
            array_push($permissions, 'email_templates');
        }
        if ($request->servers == 1) {
            array_push($permissions, 'servers');
        }
        if ($request->plans == 1) {
            array_push($permissions, 'plans');
        }
        if ($request->subscription == 1) {
            array_push($permissions, 'subscription');
        }
        if ($request->users == 1) {
            array_push($permissions, 'users');
        }
        if ($request->settings == 1) {
            array_push($permissions, 'settings');
        }
        $user->permissions = serialize($permissions);
        $user->save();
        return response()->json(['success' => true]);
    }

    public static function delete($request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->is_deleted = 1;
            $user->save();
            if ($user->parent_id == null) {
                $users = User::where('parent_id', $user->id)->get();
                foreach ($users as $sub_users) {
                    $sub_users->is_deleted = 1;
                    $sub_users->save();
                }
            }
            return response()->json(['success' => true]);
        }
    }
}