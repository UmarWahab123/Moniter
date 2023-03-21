<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWebsite extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function serverWebsites()
    {
        return $this->belongsTo('App\Server', 'server_id', 'id');
    }
}

