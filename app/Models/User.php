<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone', // Already present
        'password',
        'credits',
        'google_id',
        'trial_pack_used',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'credits' => 'integer',
        'trial_pack_used' => 'boolean',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    /**
     * Format phone number for display
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;
        
        // Remove all non-digits
        $phone = preg_replace('/\D/', '', $this->phone);
        
        // If it starts with 60, it's already international format
        if (substr($phone, 0, 2) === '60') {
            return '+' . $phone;
        }
        
        // If it starts with 0, replace with +60
        if (substr($phone, 0, 1) === '0') {
            return '+60' . substr($phone, 1);
        }
        
        // If it's just the number without country code, add +60
        return '+60' . $phone;
    }
}