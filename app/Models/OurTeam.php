<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurTeam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'media_id',
        'title',
        'content',
        'status',
        'role'
    ];
    public function getMedia(){
        return $this->hasOne('App\Models\Media','id','media_id');
    }

    public function getCategory(){
        return $this->hasOne('App\Models\Category','id','category_id');
    }
  
}

