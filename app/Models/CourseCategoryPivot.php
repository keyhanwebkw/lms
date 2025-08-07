<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Traits\Pagination;

class CourseCategoryPivot extends Model
{
    use Pagination;

    protected $table = 'coursesCategoriesPivot';
    public $timestamps = false;
    protected $fillable = [
        'courseID',
        'categoryID',
    ];

    protected $casts = [
        'ID' => 'integer',
        'courseID' => 'integer',
        'categoryID' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Course::clearCache();
        CourseCategory::clearCache();
    }
}
