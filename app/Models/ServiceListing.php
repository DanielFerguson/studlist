<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'types',
        'abn',
        'business_name',
        'contact_name',
        'phone_contact',
        'email_contact',
        'locations_covered',
        'links',
        'description',
    ];

    protected $casts = [
        'types' => 'array',
        'locations_covered' => 'array',
        'links' => 'array',
    ];

    protected static function booted(): void
    {
        // Keep legacy `type` (single) in sync for backwards compatibility/indexing.
        static::saving(function (self $serviceListing) {
            $types = $serviceListing->types;

            if (is_array($types) && count($types) > 0) {
                $serviceListing->type = $types[0];
            }
        });
    }

    /**
     * Get the user that owns the service listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



