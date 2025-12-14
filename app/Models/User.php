<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements FilamentUser
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
        'is_admin',
        'contact_business_name',
        'contact_phone',
        'contact_email',
        'contact_pic_number',
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
            'is_admin' => 'boolean',
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
     * Get the genetics listings for this user.
     */
    public function geneticsListings()
    {
        return $this->hasMany(\App\Models\GeneticsListing::class);
    }

    /**
     * Get the show equipment listings for this user.
     */
    public function showEquipmentListings()
    {
        return $this->hasMany(\App\Models\ShowEquipmentListing::class);
    }

    /**
     * Get the service listings for this user.
     */
    public function serviceListings()
    {
        return $this->hasMany(\App\Models\ServiceListing::class);
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

    /**
     * Check if this user is an admin.
     */
    public function isAdmin(): bool
    {
        if (app()->environment('local')) {
            return true;
        }

        return $this->is_admin;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    /**
     * Get contact defaults for listing forms.
     * Falls back to registered email if no contact_email is set.
     *
     * @return array<string, string|null>
     */
    public function getContactDefaults(): array
    {
        return [
            'business_contact' => $this->contact_business_name,
            'phone_contact' => $this->contact_phone,
            'email_contact' => $this->contact_email ?? $this->email,
            'pic_number' => $this->contact_pic_number,
        ];
    }

    /**
     * Save contact information from a listing form.
     *
     * @param  array<string, string|null>  $contactData
     */
    public function saveContactDefaults(array $contactData): void
    {
        $this->update([
            'contact_business_name' => $contactData['business_contact'] ?? $this->contact_business_name,
            'contact_phone' => $contactData['phone_contact'] ?? $this->contact_phone,
            'contact_email' => $contactData['email_contact'] ?? $this->contact_email,
            'contact_pic_number' => $contactData['pic_number'] ?? $this->contact_pic_number,
        ]);
    }
}
