<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShowEquipmentListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'photos',
        'condition',
        'location',
        'phone_contact',
        'email_contact',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    /**
     * Get the user that owns the show equipment listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
