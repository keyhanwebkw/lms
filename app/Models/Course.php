<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Casts\Slug;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Traits\HasArchive;
use Keyhanweb\Subsystem\Models\Traits\Pagination;

class Course extends Model
{
    use hasFactory;
    use Pagination;
    use HasArchive;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'type',
        'price',
        'discountAmount',
        'participants',
        'participantLimitation',
        'status',
        'score',
        'teacherID',
        'slug',
        'level',
        'startDate',
        'endDate',
        'managerID',
        'archived',
    ];

    protected $casts = [
        'ID' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'duration' => 'integer',
        'type' => 'string',
        'price' => 'integer',
        'discountAmount' => 'integer',
        'participants' => 'integer',
        'participantLimitation' => 'integer',
        'status' => 'string',
        'score' => 'integer',
        'teacherID' => 'integer',
        'slug' => Slug::class,
        'level' => 'string',
        'startDate' => 'integer',
        'endDate' => 'integer',
        'managerID' => 'integer',
        'archived' => 'integer',
    ];

    public static function keyCache($key = '')
    {
        return 'courses' . $key;
    }

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
        return 'courses' . $key;
    }

    public function categories()
    {
        return $this->belongsToMany(
            CourseCategory::class,
            'coursesCategoriesPivot',
            'courseID',
            'categoryID'
        );
    }

    public function courseIntro()
    {
        return $this->hasOne(CourseIntro::class);
    }

    public function courseSection()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacherID', 'ID');
    }

    public function getTotalPriceAttribute()
    {
        return $this->price - $this->discountAmount;
    }

    public function comments(): morphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attendees()
    {
        return $this->belongsToMany(
            User::class,
            'userCourses',
            'courseID',
            'userID'
        );
    }

}
