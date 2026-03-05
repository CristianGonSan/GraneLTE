<?php

namespace App\Livewire\Inventory\Responsibles;

use App\Models\Inventory\Responsible;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class ResponsibleCreate extends Component
{
    use Toast, FlashToast;

    public string $name;
    public ?string $identifier = null;
    public ?string $position = null;
    public ?string $department = null;
    public ?string $phone = null;
    public ?string $email = null;

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.inventory.responsibles.responsible-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'       => ['required', 'string', 'max:128'],
            'identifier' => ['nullable', 'string',  'max:128', Rule::unique('responsibles')],
            'position'   => ['nullable', 'string', 'max:128'],
            'department' => ['nullable', 'string', 'max:128'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:191'],
        ]);

        $Responsible = Responsible::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'identifier',
                'position',
                'department',
                'phone',
                'email'
            ]);

            $this->toastSuccess('Responsable creado');
        } else {
            $this->flashToastSuccess('Responsable creado');
            redirect()->route('responsibles.show', $Responsible->id);
        }
    }
}
