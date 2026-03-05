<?php

namespace App\Livewire\Inventory\Suppliers;

use App\Models\Inventory\Supplier;
use App\Traits\SweetAlert2\FlashAlert;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class SupplierEdit extends Component
{

    use Toast, FlashToast, Alert, FlashAlert;

    public int $supplierId;

    public string $name;
    public ?string $contact_person;
    public ?string $email;
    public ?string $phone;
    public ?string $address;
    public ?string $description;

    public function mount(int $supplierId): void
    {
        $this->supplierId       = $supplierId;

        $supplier               = $this->supplier();

        $this->name             = $supplier->name;
        $this->contact_person   = $supplier->contact_person;
        $this->email            = $supplier->email;
        $this->phone            = $supplier->phone;
        $this->address          = $supplier->address;
        $this->description      = $supplier->description;
    }

    public function render(): View
    {
        return view('livewire.inventory.suppliers.supplier-edit', [
            'supplier' => $this->supplier()
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'              => ['required', 'string', 'max:128', Rule::unique('suppliers')->ignore($this->supplierId)],
            'contact_person'    => ['nullable', 'string', 'max:128'],
            'email'             => ['nullable', 'email', 'max:191'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string', 'max:512'],
            'description'       => ['nullable', 'string', 'max:512'],
        ]);

        $this->supplier()->update($validated);

        $this->toastSuccess('Proveedor actualizado');
    }

    private ?Supplier $supplier = null;

    private function supplier(): Supplier
    {
        return $this->supplier ??= Supplier::findOrFail($this->supplierId);
    }
}
