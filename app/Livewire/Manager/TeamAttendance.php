<?php

namespace App\Livewire\Manager;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TeamAttendance extends Component
{
    use WithPagination;

    public $user;
    public $teamMembers = [];
    public $selectedDate;
    public $search = '';

    public function mount()
    {
        $this->user = Auth::user();
        $this->selectedDate = Carbon::today()->format('Y-m-d');

        // Get team members
        if ($this->user->hasRole('admin') || $this->user->hasRole('hr')) {
            $this->teamMembers = User::whereNotIn('id', [$this->user->id])
                ->orderBy('name')
                ->get();
        } else {
            $this->teamMembers = User::where('reported_to', $this->user->id)
                ->orderBy('name')
                ->get();
        }
    }

    public function render()
    {
        $selectedDate = Carbon::parse($this->selectedDate);

        $query = User::query();

        // Apply search filter
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        // Filter by reporting relationship
        if ($this->user->hasRole('admin') || $this->user->hasRole('hr')) {
            $query->whereNotIn('id', [$this->user->id]);
        } else {
            $query->where('reported_to', $this->user->id);
        }

        $attendanceData = $query->with(['attendances' => function ($query) use ($selectedDate) {
            $query->where('date', $selectedDate);
        }])
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.manager.team-attendance', [
            'attendanceData' => $attendanceData,
        ]);
    }

    public function updateSelectedDate($date)
    {
        $this->selectedDate = $date;
    }

    public function markAttendance($userId, $status)
    {
        $selectedDate = Carbon::parse($this->selectedDate);

        // Update or create attendance record
        Attendance::updateOrCreate(
            [
                'user_id' => $userId,
                'date' => $selectedDate,
            ],
            [
                'status' => $status,
            ]
        );

        session()->flash('message', 'Attendance updated successfully.');
    }
}
