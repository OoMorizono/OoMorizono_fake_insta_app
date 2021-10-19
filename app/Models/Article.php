<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'caption',
        'info',
    ];

    public function attachment()
    {
        return $this->hasOne(Attachment::class);
    }
    public function getImagePathAttribute()
    {
        return 'articles/' . $this->attachment->name;
    }
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
}
