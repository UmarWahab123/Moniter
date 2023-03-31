<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResendEmailNotification;
use App\Models\Packages\Package;
use App\Models\UserDetail;
use App\Models\UserPermission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\EmailVerificationCodeNotification;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'role_id','package_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function userRole()
    {
        return $this->hasOne('App\RoleUser', 'user_id', 'id');
    }

    public function hasAnyRoles($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }
    public function hasAnyRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }
    public function userWebsites()
    {
        return $this->hasMany('App\UserWebsite', 'user_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function resendEmail()
    {
        return $this->notify(new ResendEmailNotification(auth()->user()));
    }

    public function sendVerificationCodeEmail($verification_code)
    {
        return $this->notify(new EmailVerificationCodeNotification($verification_code));
    }

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id', 'id');
    }
    public function userpermissions()
    {
        return $this->hasMany(UserPermission::class,'user_id', 'id');
    }
    public function userdetail()
    {
        return $this->hasOne(UserDetail::class);
    }
    
}

    