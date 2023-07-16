<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
    */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'title',
        'subtitle',
        'body',
        'status',
        'image',
    ];

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_posts')->select(array('category_posts.id as cat_id', 'name'))->withTimestamps();
    }

    public function getImageAttribute($value)
    {
        return asset('uploads/posts/'. $value);
    }

}