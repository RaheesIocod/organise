<?php

namespace App\Livewire\Manager\LeaveApprovals;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filter = 'pending';

    public $search = '';

    protected $queryString = [
        'filter' => ['except' => 'pending'],
        'search' => ['except' => ''],
    ];

    public function render()
    {
        $currentUser = Auth::user();

        // Base query for leave applications
        $query = LeaveApplication::query()
            ->with(['user', 'leaveType', 'approver'])
            ->orderBy('created_at', 'desc');

        // Apply filters based on user role
        if ($currentUser->hasRole('admin') || $currentUser->hasRole('hr')) {
            // Admin or HR can see all leave applications
            if ($this->search) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            }
        } else {
            // Manager can only see leave applications from their team
            $teamMembers = User::where('reported_to', $currentUser->id)->pluck('id')->toArray();

            $query->whereIn('user_id', $teamMembers);

            if ($this->search) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            }
        }

        // Apply status filter
        if ($this->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->filter === 'approved') {
            $query->where('status', 'approved');
        } elseif ($this->filter === 'rejected') {
            $query->where('status', 'rejected');
        }

        $leaveApplications = $query->paginate(10);

        return view('livewire.manager.leave-approvals.index', [
            'leaveApplications' => $leaveApplications,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }
}
