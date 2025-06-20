<?php

namespace App\Livewire\LeaveApprovals;

use App\Models\LeaveApplication;
use App\Models\UserLeaveBalance;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public LeaveApplication $leaveApplication;
    public string $comments = '';

    protected $rules = [
        'comments' => 'nullable|string|max:500',
    ];

    public function mount(LeaveApplication $leaveApplication): void
    {
        $this->leaveApplication = $leaveApplication;

        // Verify that the current user can approve this leave
        $currentUser = Auth::user();

        if (
            !($currentUser->hasRole('admin') || $currentUser->hasRole('hr')) &&
            $leaveApplication->user->reported_to !== $currentUser->id
        ) {
            abort(403, 'You are not authorized to approve this leave application.');
        }
    }

    public function render(): View
    {
        return view('livewire.leave-approvals.show', [
            'leaveApplication' => $this->leaveApplication,
        ]);
    }

    public function approve()
    {
        $this->validate();

        // Check if the application is already approved or rejected
        if ($this->leaveApplication->status !== 'pending') {
            session()->flash('error', 'This leave application has already been ' . $this->leaveApplication->status . '.');
            return;
        }

        // Check if the user has sufficient leave balance
        if ($this->leaveApplication->leaveType->is_limited) {
            $userBalance = UserLeaveBalance::where('user_id', $this->leaveApplication->user_id)
                ->where('leave_type_id', $this->leaveApplication->leave_type_id)
                ->where('year', now()->year)
                ->first();

            if (!$userBalance || ($userBalance->total_allocated - $userBalance->used) < $this->leaveApplication->days_count) {
                session()->flash('error', 'The employee does not have enough leave balance.');
                return;
            }

            // Update the leave balance
            $userBalance->used += $this->leaveApplication->days_count;
            $userBalance->pending -= $this->leaveApplication->days_count;
            $userBalance->save();
        }

        // Update the leave application
        $this->leaveApplication->status = 'approved';
        $this->leaveApplication->approved_by = Auth::id();
        $this->leaveApplication->approved_at = now();
        $this->leaveApplication->comments = $this->comments;
        $this->leaveApplication->save();

        session()->flash('success', 'Leave application has been approved successfully.');

        return redirect()->route('manager.leave-approvals');
    }

    public function reject()
    {
        $this->validate();

        // Check if the application is already approved or rejected
        if ($this->leaveApplication->status !== 'pending') {
            session()->flash('error', 'This leave application has already been ' . $this->leaveApplication->status . '.');
            return;
        }

        // If it's a limited leave type, update the pending balance
        if ($this->leaveApplication->leaveType->is_limited) {
            $userBalance = UserLeaveBalance::where('user_id', $this->leaveApplication->user_id)
                ->where('leave_type_id', $this->leaveApplication->leave_type_id)
                ->where('year', now()->year)
                ->first();

            if ($userBalance) {
                $userBalance->pending -= $this->leaveApplication->days_count;
                $userBalance->save();
            }
        }

        // Update the leave application
        $this->leaveApplication->status = 'rejected';
        $this->leaveApplication->approved_by = Auth::id();
        $this->leaveApplication->approved_at = now();
        $this->leaveApplication->comments = $this->comments;
        $this->leaveApplication->save();

        session()->flash('success', 'Leave application has been rejected.');

        return redirect()->route('manager.leave-approvals');
    }
}
