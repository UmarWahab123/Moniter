<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function userInfo()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function serverLogs()
    {
        return $this->hasMany('App\ServerDetail', 'server_id', 'id');
    }
}
