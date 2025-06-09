<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SteerListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'photos',
        'date_of_birth',
        'breed',
        'colour',
        'location',
        'sire',
        'dam',
        'business_contact',
        'phone_contact',
        'email_contact',
        'pic_number',
        'description',
        'started_on_feed',
        'price',
        'status',
        'stripe_subscription_id',
    ];

    protected $casts = [
        'photos' => 'array',
        'date_of_birth' => 'date',
        'started_on_feed' => 'boolean',
        'price' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the listing is active (paid)
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the listing is a draft (unpaid)
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get the subscription for this listing
     */
    public function subscription()
    {
        if ($this->stripe_subscription_id) {
            return $this->user->subscriptions()->where('stripe_id', $this->stripe_subscription_id)->first();
        }

        return null;
    }

    /**
     * Get the Laravel subscription record for this listing
     */
    public function laravelSubscription()
    {
        if ($this->stripe_subscription_id) {
            return $this->user->subscriptions()->where('stripe_id', $this->stripe_subscription_id)->first();
        }

        return null;
    }

    /**
     * Check if this listing has an active subscription in Laravel's database
     */
    public function hasActiveSubscription(): bool
    {
        $subscription = $this->laravelSubscription();

        return $subscription && $subscription->active();
    }
}
