<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Casts\Slug;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;

class CourseCategory extends Model
{
    use HasFactory;

    protected $table = 'coursesCategories';

    protected $fillable = [
        'title',
        'description',
        'slug',
        'status',
        'sortOrder',
        'metaTitle',
        'metaDescription',
        'metaKeyword',
        'photoSID',
        'archived'
    ];

    protected $casts = [
        'ID' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'slug' => Slug::class,
        'status' => 'string',
        'sortOrder' => 'integer',
        'metaTitle' => 'string',
        'metaDescription' => 'string',
        'metaKeyword' => 'string',
        'photoSID' => 'string',
        'archived' => 'integer'
    ];

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
    }

    public static function cacheTag($key = '')
    {
        return 'coursesCategories' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'coursesCategories' . $key;
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'photoSID', 'SID');
    }

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'coursesCategoriesPivot',
            'categoryID',
            'courseID'
        );
    }
}
