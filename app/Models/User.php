<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasFactory, Notifiable;

    protected static function booted()
    {
        static::created(function ($user) {
            if (! $user->hasStripeId() && ! app()->environment('testing')) {
                $user->createAsStripeCustomer();
                $user->save();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the steer listings for this user.
     */
    public function steerListings()
    {
        return $this->hasMany(\App\Models\SteerListing::class);
    }

    /**
     * Get the stud listings for this user.
     */
    public function studListings()
    {
        return $this->hasMany(\App\Models\StudListing::class);
    }

    /**
     * Get the subscription for a specific steer listing.
     */
    public function subscriptionForSteer(SteerListing $steer)
    {
        if ($steer->user_id !== $this->id) {
            return null;
        }

        return $this->subscriptions()
            ->where('type', 'steer_'.$steer->id)
            ->orWhere('stripe_id', $steer->stripe_subscription_id)
            ->first();
    }

    /**
     * Check if this user has an active subscription for a specific steer listing.
     */
    public function hasActiveSubscriptionForSteer(SteerListing $steer): bool
    {
        $subscription = $this->subscriptionForSteer($steer);

        return $subscription && $subscription->active();
    }
}
