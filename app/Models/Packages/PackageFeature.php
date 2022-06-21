<?php

namespace App\Models\Packages;

use App\Models\System\SystemFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'package_id',
        'system_feature_id',
        'max_allowed_count',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function systemFeature() 
    {
        return $this->belongsTo(SystemFeature::class);
    }

  
}
