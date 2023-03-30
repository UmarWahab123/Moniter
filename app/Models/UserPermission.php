<?php

namespace App\Models;
use App\Models\Permission;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;
    protected $table = 'user_permission';
    protected $fillable = [
        'user_id',
        'permission_id',
        'type',
    ];
    public function permissions() 
    {
        return $this->belongsTo(Permission::class,'permission_id', 'id');
    }


}
