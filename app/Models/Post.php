<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'category_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function GetImagePathAttribute()
    {
        return 'public/images/posts/' . $this->image;
    }

    public function GetImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    public function GetDateDiffAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans(now());
    }

}
