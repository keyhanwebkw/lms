<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;

class AssignmentContent extends Model
{
    public $timestamps = false;
    protected $table = 'assignmentContents';

    protected $fillable = [
        'userAssignmentID',
        'text',
        'contentSID',
        'created',
    ];

    protected $casts = [
        'ID' => 'integer',
        'userAssignmentID' => 'integer',
        'text' => 'string',
        'contentSID' => 'string',
        'created' => 'integer',
    ];

    public function storage()
    {
        return $this->hasOne(Storage::class, 'SID', 'contentSID');
    }
}
