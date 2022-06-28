<?php

namespace App\Models\Packages;

use App\Models\Packages\PackageFeature;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' ,
        'price',
        'group_tag',
        'duration_in_days'
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
   
    public function packagefeatures()
    {
        return $this->hasMany(PackageFeature::class);
    }
}
