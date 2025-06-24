<?php

namespace App\Livewire\Leaves;

use App\Models\LeaveApplication;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public LeaveApplication $leave;

    public function mount(LeaveApplication $leave): void
    {
        // Ensure the user can only see their own leave applications
        // or is a manager/admin
        if ($leave->user_id !== auth()->id() && ! auth()->user()->hasRole(['manager', 'admin', 'hr'])) {
            abort(403);
        }

        $this->leave = $leave;
    }

    public function render(): View
    {
        return view('livewire.leaves.show');
    }

    public function cancel(): void
    {
        // Only allow cancellation for pending leave applications
        if ($this->leave->status !== 'pending') {
            session()->flash('error', 'Only pending leave applications can be cancelled.');

            return;
        }

        // Ensure the user can only cancel their own leave applications
        if ($this->leave->user_id !== auth()->id()) {
            abort(403);
        }

        $this->leave->status = 'cancelled';
        $this->leave->save();

        session()->flash('success', 'Leave application cancelled successfully.');
        redirect()->route('leaves');
    }
}
