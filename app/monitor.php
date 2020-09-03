<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class monitor extends Model
{
    public function getUserWebsites()
    {
        return $this->hasMany('App\UserWebsite','website_id','id');
    }
    public function getSiteDetails()
    {
        return $this->belongsTo('App\UserWebsite','id','website_id');
    }
}
