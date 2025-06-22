<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneticsListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'photos',
        'breed',
        'type',
        'sire',
        'dam',
        'registration_link',
        'storage_location',
        'phone_contact',
        'email_contact',
        'description',
    ];

    protected $casts = [
        'photos' => 'array',
        'price' => 'float',
    ];

    /**
     * Get the user that owns the genetics listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}