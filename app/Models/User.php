<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id')
            ->orWhereHas('receiver', function ($query) {
                $query->where('receiver_id', $this->id);
            });
    }
    public function scopeNearby($query, $latitude, $longitude, $distance)
    {
        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))";
        return $query->select('*')->selectRaw("$haversine AS distance")->whereRaw("$haversine <= ?", [$distance]);
    }
    public function images()
    {
        return $this->hasMany(UserImage::class);
    }
}
