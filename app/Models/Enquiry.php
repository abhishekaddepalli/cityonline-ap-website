<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'enquiry_type',
        'customer_name',
        'mobile_number',
        'email',
        'locality',
        'address',
        'interested_plan',
        'source',
        'status',
        'admin_notes',
        'assigned_to',
    ];

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
