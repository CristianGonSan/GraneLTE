<?php

namespace App\Livewire\Inventory\Responsibles;

use App\Models\Inventory\Responsible;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class ResponsibleEdit extends Component
{
    use Toast, FlashToast;

    public int $responsibleId;

    public string $name;
    public ?string $identifier = null;
    public ?string $position = null;
    public ?string $department = null;
    public ?string $phone = null;
    public ?string $email = null;

    public function mount(int $responsibleId): void
    {
        $this->responsibleId    = $responsibleId;
        $responsible            = $this->responsible();

        $this->name       = $responsible->name;
        $this->identifier = $responsible->identifier;
        $this->position   = $responsible->position;
        $this->department = $responsible->department;
        $this->phone      = $responsible->phone;
        $this->email      = $responsible->email;
    }

    public function render(): View
    {
        return view('livewire.inventory.responsibles.responsible-edit', [
            'responsible' => $this->responsible(),
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'       => ['required', 'string', 'max:128'],
            'identifier' => ['nullable', 'string', 'max:128', Rule::unique('responsibles')->ignore($this->responsibleId),],
            'position'   => ['nullable', 'string', 'max:128'],
            'department' => ['nullable', 'string', 'max:128'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:191'],
        ]);

        $this->responsible()->update($validated);

        $this->toastSuccess('Responsable actualizado');
    }

    private ?Responsible $responsible = null;

    private function responsible(): Responsible
    {
        return $this->responsible ??= Responsible::findOrFail($this->responsibleId);
    }
}
