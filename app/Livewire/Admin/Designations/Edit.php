<?php

namespace App\Livewire\Admin\Designations;

use App\Models\Designation;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    #[Title('Edit Designation')]
    public Designation $designation;

    public $name;

    public $description;

    public function mount(Designation $designation)
    {
        $this->designation = $designation;
        $this->name = $designation->name;
        $this->description = $designation->description;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:designations,name,'.$this->designation->id,
            'description' => 'nullable|string|max:255',
        ];
    }

    public function update()
    {
        $validatedData = $this->validate();

        $this->designation->update($validatedData);

        session()->flash('message', 'Designation updated successfully!');

        return $this->redirect(route('admin.designations'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.designations.edit');
    }
}
