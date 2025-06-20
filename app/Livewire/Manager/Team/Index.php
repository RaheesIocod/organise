<?php

namespace App\Livewire\Manager\Team;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;
    public $sortField = 'name';
    public $sortAsc = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortAsc' => ['except' => true],
        'showInactive' => ['except' => false],
    ];

    public function mount()
    {
        $user = Auth::user();

        // Check if user is authorized
        if (!($user->hasRole('manager') || $user->hasRole('admin') || $user->hasRole('hr'))) {
            return redirect()->route('dashboard');
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $user = Auth::user();

        // Base query
        $query = User::query();

        if ($user->hasRole('admin') || $user->hasRole('hr')) {
            // Admin/HR can see all users except themselves
            $query->where('id', '!=', $user->id);
        } else {
            // Managers can only see their direct reports
            $query->where('reported_to', $user->id);
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply active/inactive filter
        if (!$this->showInactive) {
            $query->where('is_active', true);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        $team = $query->with('designation')->paginate(10);

        // Get team stats
        $teamIds = $team->pluck('id')->toArray();

        $today = Carbon::today();
        $presentToday = Attendance::whereIn('user_id', $teamIds)
            ->where('date', $today)
            ->where('status', 'present')
            ->count();

        $leaveToday = Attendance::whereIn('user_id', $teamIds)
            ->where('date', $today)
            ->where('status', 'leave')
            ->count();

        $absentToday = count($teamIds) - $presentToday - $leaveToday;

        return view('livewire.manager.team.index', [
            'team' => $team,
            'presentToday' => $presentToday,
            'leaveToday' => $leaveToday,
            'absentToday' => $absentToday,
        ]);
    }
}
