<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Post extends Model implements HasMedia
{
    protected $fillable = ['title', 'content', 'user_id'];

    public function registerMediaCollections(Media $media): void
    {
        throw new \Exception('Not implemented');
    }
}
