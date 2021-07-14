<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    public function userInfo()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
