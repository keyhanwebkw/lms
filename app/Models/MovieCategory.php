<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Casts\Slug;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;
use Keyhanweb\Subsystem\Models\Traits\HasArchive;
use Keyhanweb\Subsystem\Models\Traits\Pagination;

class MovieCategory extends Model
{
    use HasArchive;
    use Pagination;

    protected $table = 'cg_moviesCategories';

    protected $fillable = [
        'title',
        'slug',
        'photoSID',
        'description',
        'sortOrder',
        'archived',
    ];

    protected $casts = [
        'ID' => 'integer',
        'title' => 'string',
        'slug' => Slug::class,
        'photoSID' => 'string',
        'description' => 'string',
        'sortOrder' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
    ];

    public static function cacheTag($key = '')
    {
        return 'movieCategory' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'movieCategory' . $key;
    }

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
    }

    public function movies()
    {
        return $this->belongsToMany(
            Movie::class,
            'cg_moviesCategoriesPivot',
            'categoryID',
            'movieID'
        )
            ->orderByDesc('created');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'photoSID', 'SID');
    }
}
