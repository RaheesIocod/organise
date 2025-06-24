<?php

namespace App\Livewire\Leaves;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $tab = 'pending';

    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        $query = LeaveApplication::query()->where('user_id', $this->user->id);

        if ($this->tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->tab === 'approved') {
            $query->where('status', 'approved');
        } elseif ($this->tab === 'rejected') {
            $query->where('status', 'rejected');
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(10);
        $leaveTypes = LeaveType::all();

        $leaveBalances = $this->user->leaveBalances()
            ->whereYear('created_at', Carbon::now()->year)
            ->with('leaveType')
            ->get();

        return view('livewire.leaves.index', [
            'leaves' => $leaves,
            'leaveTypes' => $leaveTypes,
            'leaveBalances' => $leaveBalances,
        ]);
    }

    public function changeTab($tab)
    {
        $this->tab = $tab;
    }
}
