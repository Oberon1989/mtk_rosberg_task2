<?php

namespace App\Models;

use Couchbase\Origin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'status', 'species', 'type', 'gender', 'origin_id', 'location_id', 'image', 'url'
    ];
    public function origin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class,'origin_id' );
    }

    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class,'location_id' );
    }

    public function episodes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->BelongsToMany(Episode::class,'characters_episodes','character_id','episode_id' );
    }

    public function getCharacterByUrl(string $url)
    {
        return Table::where('url', $url)->firstOrFail();
    }
}
