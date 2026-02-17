<?php

namespace App\Livewire\Inventory\Suppliers;

use App\Models\Inventory\Supplier;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class SupplierCreate extends Component
{
    use Toast, FlashToast;

    public string $name;
    public ?string $contact_person;
    public ?string $email;
    public ?string $phone;
    public ?string $address;
    public ?string $description;

    public bool $createAnother = false;


    public function render(): View
    {
        return view('livewire.inventory.suppliers.supplier-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'              => ['required', 'string', 'max:128', Rule::unique('suppliers')],
            'contact_person'    => ['nullable', 'string', 'max:128'],
            'email'             => ['nullable', 'email', 'max:191'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string', 'max:512'],
            'description'       => ['nullable', 'string', 'max:512'],
        ]);

        $supplier = Supplier::create($validated);

        if ($this->createAnother) {
            $this->reset(['name', 'contact_person', 'email', 'phone', 'address', 'description']);
            $this->toastSuccess('Proveedor creado.');
        } else {
            $this->flashToastSuccess('Proveedor creado.');
            redirect()->route('suppliers.index');
        }
    }
}
