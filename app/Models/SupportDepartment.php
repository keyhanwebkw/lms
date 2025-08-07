<?php

namespace App\Models;

use Keyhanweb\Subsystem\Casts\Slug;
use Keyhanweb\Subsystem\Models\Model;

class SupportDepartment extends Model
{

    protected $table = 'supportDepartments';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [
        'ID' => 'integer',
        'name' => 'string',
        'slug' => Slug::class,
        'status' => 'string',
    ];

    const STATUS = [
        'active',
        'inactive'
    ];

}
