<?php

namespace App\Livewire\Employees;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;
    public $search = '';
    public $designationFilter = '';

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        $query = User::query()
            ->with('designation')
            ->orderBy('name');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            });
        }

        // Apply designation filter
        if ($this->designationFilter) {
            $query->where('designation_id', $this->designationFilter);
        }

        $employees = $query->paginate(10);

        // Get all designations for the filter
        $designations = \App\Models\Designation::orderBy('name')->get();

        return view('livewire.employees.index', [
            'employees' => $employees,
            'designations' => $designations,
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->designationFilter = '';
    }
}
