<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Keyhanweb\Subsystem\Casts\Slug;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Traits\HasArchive;
use Keyhanweb\Subsystem\Models\Traits\Pagination;

class Movie extends Model
{
    use HasArchive;
    use Pagination;

    protected $table = 'cg_movies';

    protected $fillable = [
        'name',
        'slug',
        'posterSID',
        'description',
        'type',
        'archived',
    ];

    protected $casts = [
        'ID' => 'integer',
        'name' => 'string',
        'slug' => Slug::class,
        'posterSID' => 'string',
        'description' => 'string',
        'type' => 'string',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        MovieCategory::clearCache();
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(MovieSeason::class, 'movieID', 'ID');
    }

    public function content() // feature film content
    {
        return $this->hasOne(SeasonEpisode::class, 'movieID', 'ID');
    }

    public function categories()
    {
        return $this->belongsToMany(
            MovieCategory::class,
            'cg_moviesCategoriesPivot',
            'movieID',
            'categoryID'
        );
    }
}
