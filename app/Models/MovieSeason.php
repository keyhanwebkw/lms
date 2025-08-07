<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Traits\HasArchive;

class MovieSeason extends Model
{
    use HasArchive;

    protected $table = 'cg_movieSeasons';

    protected $fillable = [
        'movieID',
        'name',
        'sortOrder',
        'archived',
    ];

    protected $casts = [
        'ID' => 'integer',
        'movieID' => 'integer',
        'name' => 'string',
        'sortOrder' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieID');
    }

    public function episodes ()
    {
        return $this->hasMany(SeasonEpisode::class, 'seasonID');
    }

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        MovieCategory::clearCache();
    }
}
