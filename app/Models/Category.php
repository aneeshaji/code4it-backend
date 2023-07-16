<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
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
                    'source' => 'name'
               ]
          ];
     }
     
     protected $fillable = [
        'name' ,
        'status'
     ];

     public function posts()
     {
          return $this->belongsToMany('App\Models\Post','category_posts');
     }
}