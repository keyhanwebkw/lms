<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class MovieCategoryPivot extends Model
{
    public $timestamps = false;

    protected $table = 'cg_moviesCategoriesPivot';

    protected $fillable = [
        'movieID',
        'categoryID',
    ];

    protected $casts = [
        'ID' => 'integer',
        'movieID' => 'integer',
        'categoryID' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        MovieCategory::clearCache();
    }
}
