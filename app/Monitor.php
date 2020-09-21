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
        return $this->belongsTo('App\WebsiteLog','id','website_id');
    } 

}
