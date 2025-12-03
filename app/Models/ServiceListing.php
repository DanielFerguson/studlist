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
        'type',
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
        'locations_covered' => 'array',
        'links' => 'array',
    ];

    /**
     * Get the user that owns the service listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}







