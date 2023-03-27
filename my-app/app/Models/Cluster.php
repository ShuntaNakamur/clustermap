<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'latitude',
        'longitude',
        'user_id'
    ];
    public function likes()
    {
        return $this->belongsToMany('App\Models\User','likes','cluster_id','user_id')->count();
    }

}
