<?php

namespace App\Models\System;

use App\Models\Packages\PackageFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function packageFeatures()
    {
        return $this->belongsToMany(PackageFeature::class);
    }
}
