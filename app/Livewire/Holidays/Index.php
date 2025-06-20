<?php

namespace App\Livewire\Holidays;

use App\Models\Holiday;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $viewMode = 'table';

    public function render(): View
    {
        $holidays = Holiday::query()
            ->orderBy('date')
            ->paginate(10);

        return view('livewire.holidays.index', [
            'holidays' => $holidays
        ]);
    }

    public function setViewMode($mode): void
    {
        $this->viewMode = $mode;
    }
}
