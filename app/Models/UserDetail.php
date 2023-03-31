<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $table = 'users_details';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'primary_notification_email',
        'secondary_notification_email',
        'developer_email',
    ];

}
