<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public const AMENITIES = [
        'Drinking Water' => 'Drinking Water',
        'Wi-Fi Connection' => 'Wi-Fi Connection',
        'News Paper' => 'News Paper',
        'Power Backup' => 'Power Backup',
        'Conference Room' => 'Conference Room',
        'Meditation Hall' => 'Meditation Hall',
        'Air Condition' => 'Air Condition',
        'Chapel' => 'Chapel',
        'CCTV camera' => 'CCTV camera',
        'Reception' => 'Reception',
        'Individual Bathrooms' => 'Individual Bathrooms',
        'Individual Bed' => 'Individual Bed',
        'Spacious Hall' => 'Spacious Hall',
        'Out Side View' => 'Out Side View',
        'Garden' => 'Garden',
        'Site Out' => 'Site Out',
        'Dinning Hall' => 'Dinning Hall',
        'AC Meeting Hall' => 'AC Meeting Hall',
        'Corridor' => 'Corridor',
        'Photo Shoot' => 'Photo Shoot',
        'AC Rooms' => 'AC Rooms',
    ];
    function getSlug(){
        return $this->hasOne('App\Models\Slug','id','slug_id');
    }
    function getCategory(){
        return $this->hasOne('App\Models\Category','id','category_id');
    }
    function getMedia(){
        return $this->hasOne('App\Models\Media','id','media_id');
    }
    function getComments(){
        return $this->hasMany('App\Models\Comment');
    }
}
