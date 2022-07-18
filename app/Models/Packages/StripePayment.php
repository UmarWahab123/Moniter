<?php

namespace App\Models\Packages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    use HasFactory;
    protected $table = 'stripe_payment';
    protected $fillable = [
        'user_id',
        'package_id',
        'detail',
        'currency',
        'payment_intant_id',
        'customer_id',
        'subscription_id',
        'is_subscribed',
    ];

    public function user()
    {
    
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
