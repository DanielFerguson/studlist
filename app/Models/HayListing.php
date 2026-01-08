<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HayListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'hay_type',
        'bale_type',
        'quantity',
        'weight_per_bale',
        'season_cut',
        'cut_year',
        'quality_grade',
        'test_results_available',
        'protein_percentage',
        'moisture_percentage',
        'energy_mj_kg',
        'nitrate_level',
        'weather_damaged',
        'storage_type',
        'location',
        'latitude',
        'longitude',
        'delivery_available',
        'delivery_radius_km',
        'minimum_order_quantity',
        'price_type',
        'price_per_bale',
        'price_per_tonne',
        'business_contact',
        'phone_contact',
        'email_contact',
        'pic_number',
        'photos',
        'description',
    ];

    protected $casts = [
        'photos' => 'array',
        'quantity' => 'integer',
        'weight_per_bale' => 'float',
        'cut_year' => 'integer',
        'test_results_available' => 'boolean',
        'protein_percentage' => 'float',
        'moisture_percentage' => 'float',
        'energy_mj_kg' => 'float',
        'weather_damaged' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'delivery_available' => 'boolean',
        'delivery_radius_km' => 'integer',
        'minimum_order_quantity' => 'integer',
        'price_per_bale' => 'float',
        'price_per_tonne' => 'float',
    ];

    protected $appends = ['display_price'];

    /**
     * Get the user that owns the hay listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted display price.
     */
    public function getDisplayPriceAttribute(): ?string
    {
        if ($this->price_type === 'Negotiable') {
            return 'Negotiable';
        }

        if ($this->price_per_bale) {
            return '$'.number_format($this->price_per_bale, 2).'/bale';
        }

        if ($this->price_per_tonne) {
            return '$'.number_format($this->price_per_tonne, 2).'/tonne';
        }

        return null;
    }

    /**
     * Scope for listings with coordinates (for map display).
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }
}
