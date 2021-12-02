<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'country_id',
        'name',
        'email',
        'about',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeCountry($query, $short_code)
    {
        return $query->whereHas('country', function ($query) use ($short_code) {
            $query->where('short_code', $short_code);
        });
    }

    public function scopeRegisteredFrom($query, $date)
    {
        return $query->whereDate('created_at', '>=', $date);
    }

    public function scopeRegisteredTo($query, $date)
    {
        return $query->whereDate('created_at', '<=', $date);
    }
}
