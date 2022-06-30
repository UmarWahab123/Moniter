<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWebsitePermission extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
