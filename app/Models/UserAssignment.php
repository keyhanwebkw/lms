<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class UserAssignment extends Model
{
    protected $table = 'userAssignments';

    protected $fillable = [
        'assignmentID',
        'userID',
        'managerResponse',
        'status',
        'receivedScore',
        'retryCount',
    ];

    protected $casts = [
        'ID'=> 'integer',
        'assignmentID'=> 'integer',
        'userID'=> 'integer',
        'managerResponse'=> 'string',
        'status'=> 'string',
        'receivedScore'=> 'integer',
        'retryCount'=> 'integer',
        'created'=> 'integer',
        'updated'=> 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userID');
    }

    public function content()
    {
        return $this->hasMany(AssignmentContent::class,'userAssignmentID','ID');
    }
}
