<?php

namespace App\Models\Packages;

use App\Models\Packages\PackageFeature;
use App\Models\System\SystemFeature;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' ,
        'description',
        'type',
        'status',
        'price',
        'no_of_servers',
        'no_of_websites',
        'no_of_users'
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
   
    public function packagefeatures()
    {
        return $this->hasMany(PackageFeature::class);
    }
    public function stripeSubscription()
    {
        return $this->hasOne(StripePayment::class);
    }
    // public function packageSubscription()
    // {
    //     return $this->belongsTo(User::class, 'package_id', 'id');
    // }
    public function features()
    {
        return $this->belongsToMany(SystemFeature::class);
    }
}
