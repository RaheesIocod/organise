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

    public $isHalfDay = false; // Legacy - keeping for backward compatibility

    public $halfDayType = 'morning'; // Legacy - keeping for backward compatibility

    // New properties for time-of-day parts
    public $startTimePart = 'morning'; // 'morning' or 'afternoon'

    public $endTimePart = 'end_of_day'; // 'morning' or 'end_of_day'

    // Calendar related properties
    public $currentMonth;

    public $currentYear;

    public $daysInMonth = [];

    public $selectionMode = 'start'; // 'start' or 'end'

    public $leaveTypeDetails = null;

    // Dropdown state
    public $showLeaveTypeDropdown = false;

    // UI State
    public $showHint = false;

    protected $rules = [
        'leaveTypeId' => 'required|exists:leave_types,id',
        'fromDate' => 'required|date|after_or_equal:today',
        'toDate' => 'required|date|after_or_equal:fromDate',
        'reason' => 'required|string|min:5|max:500',
        'startTimePart' => 'required|in:morning,afternoon',
        'endTimePart' => 'required|in:morning,end_of_day',
    ];

    protected $messages = [
        'leaveTypeId.required' => 'Please select a leave type.',
        'fromDate.required' => 'Please select a start date.',
        'fromDate.after_or_equal' => 'Start date must be today or in the future.',
        'toDate.required' => 'Please select an end date.',
        'toDate.after_or_equal' => 'End date must be after or the same as the start date.',
        'reason.required' => 'Please provide a reason for your leave.',
        'startTimePart.required' => 'Please select start time of day.',
        'endTimePart.required' => 'Please select end time of day.',
    ];

    public function mount(): void
    {
        // Default to today
        $this->fromDate = Carbon::now()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');

        // Set current month and year for calendar
        $this->currentMonth = Carbon::now()->month - 1; // JavaScript months are 0-based
        $this->currentYear = Carbon::now()->year;

        // Generate calendar days
        $this->generateDaysInMonth();
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function updatedLeaveTypeId($value): void
    {
        if ($value) {
            $this->leaveTypeDetails = LeaveType::find($value);
        } else {
            $this->leaveTypeDetails = null;
        }
    }

    // Calendar related functions
    public function generateDaysInMonth(): void
    {
        $this->daysInMonth = [];

        // JavaScript months are 0-based, Carbon months are 1-based
        $month = $this->currentMonth + 1;

        // Get first day of the month
        $firstDay = Carbon::createFromDate($this->currentYear, $month, 1)->dayOfWeek;
        // Get days in month
        $daysInMonth = Carbon::createFromDate($this->currentYear, $month)->daysInMonth;

        // Add empty slots for days from previous month
        for ($i = 0; $i < $firstDay; $i++) {
            $this->daysInMonth[] = [
                'day' => '',
                'isCurrentMonth' => false,
            ];
        }

        // Add days of the current month
        $today = Carbon::today();
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::createFromDate($this->currentYear, $month, $i);
            $dateString = $date->format('Y-m-d');

            $this->daysInMonth[] = [
                'day' => $i,
                'isCurrentMonth' => true,
                'date' => $dateString,
                'isToday' => $date->isSameDay($today),
                'isPast' => $date->lt($today),
            ];
        }
    }

    public function prevMonth(): void
    {
        if ($this->currentMonth === 0) {
            $this->currentMonth = 11;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }

        $this->generateDaysInMonth();
    }

    public function nextMonth(): void
    {
        if ($this->currentMonth === 11) {
            $this->currentMonth = 0;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }

        $this->generateDaysInMonth();
    }

    public function setSelectionMode($mode): void
    {
        $this->selectionMode = $mode;
    }

    public function selectDate($date): void
    {
        if (! $date) {
            return;
        }

        if ($this->selectionMode === 'start') {
            // Set start date
            $this->fromDate = $date;

            // If end date is before or equal to start date, reset it to start date
            if (! $this->toDate || Carbon::parse($this->toDate)->lt(Carbon::parse($this->fromDate))) {
                $this->toDate = $date;
            }

            // Switch to end date selection
            $this->selectionMode = 'end';
        } else {
            // Don't allow end date before start date
            if (Carbon::parse($date)->gte(Carbon::parse($this->fromDate))) {
                $this->toDate = $date;
            } else {
                // If trying to select an end date before start date,
                // set it as the new start date
                $this->fromDate = $date;
                $this->toDate = $date;
            }
        }
    }

    public function isInRange($date): bool
    {
        if (! $date || ! $this->fromDate || ! $this->toDate) {
            return false;
        }

        $date = Carbon::parse($date);
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        // Simple checks for exact matches (always in range)
        if ($date->isSameDay($fromDate) || $date->isSameDay($toDate)) {
            return true;
        }

        // Ensure fromDate is before toDate for range comparison
        if ($fromDate->gt($toDate)) {
            $temp = $fromDate;
            $fromDate = $toDate;
            $toDate = $temp;
        }

        // Now check if date falls between fromDate and toDate
        return $date->gt($fromDate) && $date->lt($toDate);
    }

    public function formatDate($date): string
    {
        if (! $date) {
            return '';
        }

        return Carbon::parse($date)->format('M j, Y');
    }

    // Format date for display in the UI
    public function formatDateForDisplay($date): string
    {
        if (!$date) {
            return '';
        }

        return Carbon::parse($date)->format('M j, Y');
    }

    public function getCalendarDayClass($day): string
    {
        if (! isset($day['date']) || ! $day['isCurrentMonth']) {
            return '';
        }

        $classes = ['rounded-full', 'w-6', 'h-6', 'mx-auto', 'flex', 'items-center', 'justify-center', 'text-xs'];

        if ($day['date'] === $this->fromDate) {
            return implode(' ', array_merge($classes, ['bg-indigo-600', 'text-white', 'font-bold']));
        }

        if ($day['date'] === $this->toDate) {
            return implode(' ', array_merge($classes, ['bg-purple-600', 'text-white', 'font-bold']));
        }

        if ($this->isInRange($day['date'])) {
            return implode(' ', array_merge($classes, ['bg-indigo-100', 'text-indigo-800']));
        }

        if ($day['isToday']) {
            return implode(' ', array_merge($classes, ['bg-green-500', 'text-white', 'font-bold']));
        }

        if ($day['isPast'] && $this->selectionMode === 'start') {
            return implode(' ', array_merge($classes, ['text-gray-400', 'cursor-not-allowed']));
        }

        return implode(' ', array_merge($classes, ['hover:bg-gray-100']));
    }

    public function showLeaveTypeDetails($id): void
    {
        if ($id) {
            $this->leaveTypeDetails = LeaveType::find($id);
        } else {
            $this->leaveTypeDetails = null;
        }
    }

    // Reset leave type selection
    public function resetLeaveType()
    {
        $this->leaveTypeId = '';
        $this->leaveTypeDetails = null;
        $this->showLeaveTypeDropdown = false;
    }

    // Select a leave type and update related data
    public function selectLeaveType($id)
    {
        $this->leaveTypeId = $id;
        $this->showLeaveTypeDetails($id);
        $this->showLeaveTypeDropdown = false;
    }

    // Calculate days count as a computed property
    public function getDaysCountProperty()
    {
        return $this->calculateDaysCount();
    }

    public function submit()
    {
        $this->validate();

        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        // Make sure fromDate is not after toDate
        if ($fromDate->gt($toDate)) {
            $temp = $fromDate;
            $fromDate = $toDate;
            $toDate = $temp;

            // Update component properties to reflect swapped dates
            $this->fromDate = $fromDate->format('Y-m-d');
            $this->toDate = $toDate->format('Y-m-d');
        }

        // Use the calculateDaysCount method for consistency
        $daysCount = $this->calculateDaysCount();

        // Continue with the logic for adjusting based on time parts
        if ($fromDate->isSameDay($toDate)) {
            // Same day - determine if it's half or full day
            if ($this->startTimePart === 'morning' && $this->endTimePart === 'end_of_day') {
                // Full day (morning to end of day)
                $daysCount = 1;
                $this->isHalfDay = false;
            } elseif ($this->startTimePart === 'afternoon' && $this->endTimePart === 'end_of_day') {
                // Half day (afternoon only)
                $daysCount = 0.5;
                $this->isHalfDay = true;
                $this->halfDayType = 'afternoon';
            } elseif ($this->startTimePart === 'morning' && $this->endTimePart === 'morning') {
                // Half day (morning only)
                $daysCount = 0.5;
                $this->isHalfDay = true;
                $this->halfDayType = 'morning';
            } else {
                // Other combinations - treat as full day
                $daysCount = 1;
                $this->isHalfDay = false;
            }
        } else {
            // Multiple days - adjust for partial first and last day if needed
            if ($this->startTimePart === 'afternoon') {
                // Starting in afternoon reduces by 0.5
                $daysCount -= 0.5;
            }

            if ($this->endTimePart === 'morning') {
                // Ending in morning reduces by 0.5
                $daysCount -= 0.5;
            }

            // Multi-day leaves can't be half-day
            $this->isHalfDay = false;
        }

        // Check if the user has enough leave balance
        $leaveType = LeaveType::findOrFail($this->leaveTypeId);
        if ($leaveType->is_limited) {
            $userId = Auth::check() ? Auth::id() : null;
            $userBalance = UserLeaveBalance::where('user_id', $userId)
                ->where('leave_type_id', $this->leaveTypeId)
                ->where('year', now()->year)
                ->first();

            if (! $userBalance || ($userBalance->total_allocated - $userBalance->used) < $daysCount) {
                session()->flash('error', 'You do not have enough leave balance.');

                return;
            }
        }

        // Check if the leave requires advance notice
        if (! $leaveType->ignore_notice && $leaveType->advance_notice_days > 0) {
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
            'start_time_part' => $this->startTimePart,
            'end_time_part' => $this->endTimePart,
        ]);

        session()->flash('success', 'Leave application submitted successfully.');

        // Redirect to leave applications list
        return redirect()->route('leaves');
    }

    // Helper function to calculate leave duration
    public function calculateDaysCount()
    {
        if (! $this->fromDate || ! $this->toDate) {
            return 0;
        }

        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        // Make sure fromDate is not after toDate
        if ($fromDate->gt($toDate)) {
            $temp = $fromDate;
            $fromDate = $toDate;
            $toDate = $temp;
        }

        // Calculate days difference (always positive)
        $daysCount = $fromDate->diffInDays($toDate) + 1;

        // Same day calculations
        if ($fromDate->isSameDay($toDate)) {
            if ($this->startTimePart === 'morning' && $this->endTimePart === 'end_of_day') {
                return 1; // Full day
            } elseif (($this->startTimePart === 'morning' && $this->endTimePart === 'morning') ||
                ($this->startTimePart === 'afternoon' && $this->endTimePart === 'end_of_day')
            ) {
                return 0.5; // Half day
            } else {
                return 1; // Default
            }
        }

        // Multiple day calculations
        if ($this->startTimePart === 'afternoon') {
            $daysCount -= 0.5; // Starting in afternoon
        }

        if ($this->endTimePart === 'morning') {
            $daysCount -= 0.5; // Ending in morning
        }

        // Ensure we never return less than 0 and format the number nicely
        $daysCount = max(0, $daysCount);

        // Format decimal points properly (0.5 stays 0.5, whole numbers have no decimal)
        return $daysCount == (int)$daysCount ? (int)$daysCount : $daysCount;
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
}
