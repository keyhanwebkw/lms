<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;

class Setting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'type',
        'relatedTo',
        'limit',
        'updated',
    ];

    protected $casts = [
        'ID' => 'integer',
        'key' => 'string',
        'type' => 'string',
        'relatedTo' => 'string',
        'limit' => 'string',
        'updated' => 'integer',
    ];

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return match ($attributes['type']) {
                    'integer' => (int)$value,
                    'string' => (string)$value,
                    'boolean' => (bool)$value,
                    'json' => $value ? json_decode($value, true) : [],
                    default => $value,
                };
            }
        );
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
        return 'setting' . $key;
    }
    public static function cacheKey($key = '')
    {
        return 'setting' . $key;
    }
}
