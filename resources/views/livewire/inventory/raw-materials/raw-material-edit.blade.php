<div>
    <h1 class="h4"> {{ $rawMaterial->mediumText('name') }}</h1>

    <div class="row">
        <div class="col-md-8">
            <form wire:submit.prevent="save">
                <div class="card mb-3">
                    <div class="card-body form-row">
                        <x-adminlte-input fgroup-class="col-md-4" name="name" label="Nombre *"
                            placeholder="Ingrese el nombre de la materia prima" type="text" wire:model="name"
                            maxlength="128" required autofocus />

                        <x-adminlte-input fgroup-class="col-md-4" class="text-uppercase" name="abbreviation"
                            label="Abreviatura *" placeholder="Ej: KG, LT" type="text" wire:model="abbreviation"
                            maxlength="8" title="Se usara como prefijo en el codigo de los lotes" :readonly="$hasBatches"
                            required />

                        <x-adminlte-input fgroup-class="col-md-4" name="minimum_stock" label="Stock mínimo"
                            placeholder="0.000" type="number" step="0.001" wire:model="minimum_stock" />

                        <x-form.select-wire-ignore fgroup-class="col-md-6" name="unit_id" label="Unidad *"
                            wire:loading.attr='readonly' wire:target='save' required>
                        </x-form.select-wire-ignore>

                        <x-form.select-wire-ignore fgroup-class="col-md-6" name="category_id" label="Categoría *"
                            wire:loading.attr='readonly' wire:target='save' required>
                        </x-form.select-wire-ignore>

                        <x-adminlte-textarea fgroup-class="col-md-12" name="description" label="Descripción"
                            placeholder="Ingrese una descripción..." wire:model="description" rows="3"
                            maxlength="255" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-livewire.loading-button type="submit" label="Guardar cambios" class="mr-1" />

                    <a href="{{ route('raw-materials.index') }}" class="btn btn-outline-secondary"
                        wire:loading.attr="disabled">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            @include('partials.livewire.edit.model-status', [
                'model' => $rawMaterial
            ])
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            let $wire = Livewire.first();

            let select2Builder = new LivewireSelect2Builder($wire);

            const unitSelect = select2Builder.selector('#unit_id').wireModel('unit_id')
                .value(@json($unit_id), @json($unitText))
                .appendConfig({
                    placeholder: 'Seleccionar unidad',
                    ajax: {
                        url: "{{ route('lookups.units.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    },
                    templateResult: data => {
                        if (data.loading) return data.text;
                        return $(`
                        <div class="p-1">
                            <strong class="d-block">${data.description}</strong>
                            <small>${data.text}</small>
                        </div>
                        `);
                    }
                }).build();

            const categorySelect = select2Builder.selector('#category_id').wireModel('category_id')
                .value(@json($category_id), @json($categoryText))
                .appendConfig({
                    placeholder: 'Seleccionar categoria',
                    ajax: {
                        url: "{{ route('lookups.categories.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    },
                    templateResult: data => {
                        if (data.loading) return data.text;
                        return $(`
                        <div class="p-1">
                            <strong class="d-block">${data.text}</strong>
                            <small>${data.description ?? 'Sin descripción'}</small>
                        </div>
                        `);
                    }
                }).build();
        });
    </script>
@endpush
