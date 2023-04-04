<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteHistory extends Model
{
    use HasFactory;
    protected $fillable =[
        'column_name',
        'old_value',
        'new_value',
        'updated_by',
    ];
}
