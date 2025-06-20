<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'annual_quota',
        'is_limited',
        'advance_notice_days',
        'ignore_notice',
        'description',
        'days_allowed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_limited' => 'boolean',
        'ignore_notice' => 'boolean',
    ];

    /**
     * Get the leave applications for the leave type.
     */
    public function leaveApplications(): HasMany
    {
        return $this->hasMany(LeaveApplication::class);
    }

    /**
     * Get the user leave balances for the leave type.
     */
    public function userLeaveBalances(): HasMany
    {
        return $this->hasMany(UserLeaveBalance::class);
    }
}
