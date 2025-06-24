<?php

namespace App\Livewire\Reports;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\UserLeaveBalance;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Leaves extends Component
{
    public $year;

    public $user;

    public $leaveTypes = [];

    public $leaveBalances = [];

    public $leaveHistory = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->year = now()->year;
        $this->loadLeaveData();
    }

    public function render()
    {
        return view('livewire.reports.leaves');
    }

    public function changeYear($direction)
    {
        if ($direction === 'prev') {
            $this->year--;
        } else {
            $this->year++;
        }

        $this->loadLeaveData();
    }

    protected function loadLeaveData()
    {
        // Get all leave types
        $this->leaveTypes = LeaveType::all();

        // Get leave balances for the year
        $this->leaveBalances = UserLeaveBalance::where('user_id', $this->user->id)
            ->where('year', $this->year)
            ->with('leaveType')
            ->get();

        // Get leave history for the year
        $this->leaveHistory = LeaveApplication::where('user_id', $this->user->id)
            ->whereYear('from_date', $this->year)
            ->with(['leaveType', 'approver'])
            ->orderBy('from_date', 'desc')
            ->get();
    }
}
