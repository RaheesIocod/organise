<?php

namespace App\Livewire\Leaves;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\UserLeaveBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    public $leaveTypeId;
    public $fromDate;
    public $toDate;
    public $reason;
    public $isHalfDay = false;
    public $halfDayType = 'morning';

    protected $rules = [
        'leaveTypeId' => 'required|exists:leave_types,id',
        'fromDate' => 'required|date|after_or_equal:today',
        'toDate' => 'required|date|after_or_equal:fromDate',
        'reason' => 'required|string|min:5|max:500',
        'isHalfDay' => 'boolean',
        'halfDayType' => 'required_if:isHalfDay,true|in:morning,afternoon',
    ];

    protected $messages = [
        'leaveTypeId.required' => 'Please select a leave type.',
        'fromDate.required' => 'Please select a start date.',
        'fromDate.after_or_equal' => 'Start date must be today or in the future.',
        'toDate.required' => 'Please select an end date.',
        'toDate.after_or_equal' => 'End date must be after or the same as the start date.',
        'reason.required' => 'Please provide a reason for your leave.',
        'halfDayType.required_if' => 'Please select which half of the day (morning or afternoon).',
    ];

    public function mount(): void
    {
        // Default to today
        $this->fromDate = Carbon::now()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render(): View
    {
        $leaveTypes = LeaveType::all();

        $userId = Auth::check() ? Auth::id() : null;
        $userLeaveBalances = UserLeaveBalance::where('user_id', $userId)
            ->where('year', now()->year)
            ->with('leaveType')
            ->get();

        return view('livewire.leaves.create', [
            'leaveTypes' => $leaveTypes,
            'userLeaveBalances' => $userLeaveBalances,
        ]);
    }

    public function submit()
    {
        $this->validate();

        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        // Calculate days count, handling half-day logic
        $daysCount = $toDate->diffInDays($fromDate) + 1;

        // If it's a half-day leave, adjust the days count
        if ($this->isHalfDay) {
            // For half-day leaves, reduce the count by 0.5
            // This only makes sense when from date and to date are the same
            if ($fromDate->isSameDay($toDate)) {
                $daysCount = 0.5;
            } else {
                // Multi-day half-days not supported, reset to full day
                $this->isHalfDay = false;
            }
        }

        // Check if the user has enough leave balance
        $leaveType = LeaveType::findOrFail($this->leaveTypeId);
        if ($leaveType->is_limited) {
            $userId = Auth::check() ? Auth::id() : null;
            $userBalance = UserLeaveBalance::where('user_id', $userId)
                ->where('leave_type_id', $this->leaveTypeId)
                ->where('year', now()->year)
                ->first();

            if (!$userBalance || ($userBalance->total_allocated - $userBalance->used) < $daysCount) {
                session()->flash('error', 'You do not have enough leave balance.');
                return;
            }
        }

        // Check if the leave requires advance notice
        if (!$leaveType->ignore_notice && $leaveType->advance_notice_days > 0) {
            $requiredNoticeDate = Carbon::now()->addDays($leaveType->advance_notice_days);
            if ($fromDate->lessThan($requiredNoticeDate)) {
                session()->flash('error', "This leave type requires {$leaveType->advance_notice_days} days advance notice.");
                return;
            }
        }

        // Create the leave application
        $leaveApplication = LeaveApplication::create([
            'user_id' => Auth::id(),
            'leave_type_id' => $this->leaveTypeId,
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
            'reason' => $this->reason,
            'days_count' => $daysCount,
            'status' => 'pending',
            'is_half_day' => $this->isHalfDay,
            'half_day_type' => $this->isHalfDay ? $this->halfDayType : null,
        ]);

        session()->flash('success', 'Leave application submitted successfully.');

        // Redirect to leave applications list
        return redirect()->route('leaves');
    }
}
