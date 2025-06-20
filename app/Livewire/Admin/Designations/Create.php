<?php

namespace App\Livewire\Admin\Designations;

use App\Models\Designation;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    #[Title('Create Designation')]
    public $name;
    public $description;

    protected $rules = [
        'name' => 'required|string|max:100|unique:designations,name',
        'description' => 'nullable|string|max:255',
    ];

    public function save()
    {
        $validatedData = $this->validate();

        Designation::create($validatedData);

        session()->flash('message', 'Designation created successfully!');

        return $this->redirect(route('admin.designations'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.designations.create');
    }
}
