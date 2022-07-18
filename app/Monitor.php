<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitor extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    public function getUserWebsites()
    {
        return $this->hasMany('App\UserWebsite', 'website_id', 'id');
    }
    public function getSiteDetails()
    {
        return $this->belongsTo('App\UserWebsite', 'id', 'website_id');
    }

    public function getSiteLogs()
    {
        return $this->hasMany('App\WebsiteLog', 'website_id', 'id')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function UserWebsitePivot()
    {
        return $this->hasMany('App\UserWebsitePermission', 'website_id', 'id');
    }
}
