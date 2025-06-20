<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'dob',
        'doj',
        'designation_id',
        'reported_to',
        'joining_experience_years',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'doj' => 'date',
        ];
    }

    /**
     * Get the company experience in years.
     */
    public function getCompanyExperienceAttribute(): float
    {
        if (!$this->doj) {
            return 0;
        }

        return round(Carbon::parse($this->doj)->diffInDays(Carbon::now()) / 365, 2);
    }

    /**
     * Get the total experience in years.
     */
    public function getTotalExperienceAttribute(): float
    {
        return $this->joining_experience_years + $this->company_experience;
    }

    /**
     * Get the designation that owns the user.
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Get the manager that the user reports to.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_to');
    }

    /**
     * Get the team members that report to this user.
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(User::class, 'reported_to');
    }

    /**
     * Get the leave applications for the user.
     */
    public function leaveApplications(): HasMany
    {
        return $this->hasMany(LeaveApplication::class);
    }

    /**
     * Get the leave balances for the user.
     */
    public function leaveBalances(): HasMany
    {
        return $this->hasMany(UserLeaveBalance::class);
    }

    /**
     * Get the task time entries for the user.
     */
    public function taskTimeEntries(): HasMany
    {
        return $this->hasMany(TaskTimeEntry::class);
    }

    /**
     * Get the attendances for the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
