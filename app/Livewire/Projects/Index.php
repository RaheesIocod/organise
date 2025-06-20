<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
    ];

    public function render()
    {
        $query = Project::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $projects = $query->orderBy('end_date')
            ->paginate(10);

        return view('livewire.projects.index', [
            'projects' => $projects
        ]);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
