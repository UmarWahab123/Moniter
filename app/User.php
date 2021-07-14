<?php

namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
        return null!==$this->roles()->whereIn('name',$roles)->first();
    }
    public function hasAnyRole($role)
    {
        return null!==$this->roles()->where('name',$role)->first();
    }
    public function userWebsites()
    {
        return $this->hasMany('App\UserWebsite','user_id','id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    public function getJWTCustomClaims()
    {
        return [];
    }

}
