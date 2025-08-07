<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class UserAnswers extends Model
{
    protected $table = 'userAnswers';
    public $timestamps = false;

    protected $fillable = [
        'userExamID',
        'questionID',
        'optionID',
        'created',
    ];

    protected $casts = [
        'ID' => 'integer',
        'userExamID' => 'integer',
        'questionID' => 'integer',
        'optionID' => 'integer',
        'created' => 'integer',
    ];
}
