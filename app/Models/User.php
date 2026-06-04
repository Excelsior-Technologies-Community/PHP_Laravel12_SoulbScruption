<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasSubscriptions;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function checkFeature($featureName)
    {
       $subscription = $this->subscription;

    if (!$subscription) {
        return null;
    }

    return $subscription->plan->features()->where('name', $featureName)->first();
    }
}