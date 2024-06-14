<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(UserImageLike::class , 'image_id');
    }
    
    public function comments()
    {
        return $this->hasMany(UserImageComment::class ,'image_id');
    }
    

}
