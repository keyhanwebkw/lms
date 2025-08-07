<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class UserCourse extends Model
{
    protected $table = 'userCourses';
    protected $fillable = [
        'courseID',
        'userID',
        'status',
        'created',
        'updated',
    ];

    protected $casts = [
        'ID' => 'integer',
        'courseID' => 'integer',
        'userID' => 'integer',
        'status' => 'string',
        'created' => 'integer',
        'updated' => 'integer',
    ];
}
