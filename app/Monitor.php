<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    public function getUserWebsites()
    {
        return $this->hasMany('App\UserWebsite','website_id','id');
    }
    public function getSiteDetails()
    {
        return $this->belongsTo('App\UserWebsite','id','website_id');
    } 

    public function getSiteLogs()
    {
        return $this->hasMany('App\WebsiteLog','website_id','id')->orderBy('created_at','desc');
    } 

}
