<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name','type','dimension','url'];
    public function characters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Character::class,'location_id','id');
    }

    public function originCharacters(): \Illuminate\Database\Eloquent\Relations\HasMany{
        return $this->hasMany(Character::class,'origin_id','id');
    }
}
