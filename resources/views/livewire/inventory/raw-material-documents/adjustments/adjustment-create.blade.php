<div>
    <h1 class="h4">Crear Ajuste de Materia Prima</h1>

    <form wire:submit.prevent="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-3" name="effective_at" label="Fecha efectiva *" type="datetime-local"
                    wire:model="effective_at" required />

                <x-adminlte-input fgroup-class="col-md-3" name="reference_type" label="Tipo de referencia"
                    placeholder="Motivo, folio, etc." type="text" wire:model="reference_type" maxlength="32" />

                <x-adminlte-input fgroup-class="col-md-3" name="reference_number" label="Número de referencia"
                    placeholder="Número de referencia" type="text" wire:model="reference_number" maxlength="128" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="responsible_id" label="Responsable"
                    wire:loading.attr="readonly" wire:target="save" />

                <x-adminlte-textarea fgroup-class="col-md-12" name="description" label="Descripción"
                    placeholder="Descripción del ajuste" wire:model="description" rows="2" maxlength="255" />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="isDraft" label="Guardar como borrador" title="Guarda este documento como borrador"
                        wire:model="isDraft" />
                </div>
            </div>
        </div>

        <h2 class="h5">Lista de existencias</h2>

        @include('partials.livewire.inventory.raw-material-documents.adjustments.lines')

        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Validar documento" class="mr-1" />

            <a href="{{ route('raw-material-documents.index') }}" class="btn btn-outline-secondary"
                wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            const $wire = Livewire.first();

            let select2Builder = new LivewireSelect2Builder($wire);

            select2Builder
                .selector('#responsible_id')
                .wireModel('responsible_id')
                .appendConfig({
                    placeholder: 'Seleccionar responsable',
                    ajax: {
                        url: "{{ route('lookups.responsibles.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function(params) {
                            return {
                                term: params.term,
                                active: true,
                            };
                        },
                    },
                    templateResult: data => {
                        if (data.loading) return data.text;
                        return $(`
                        <div class="p-1">
                            <strong class="d-block">${data.text}</strong>
                            <small>${data.description ?? ''}</small>
                        </div>
                    `);
                    }
                })
                .build();
        });
    </script>
@endpush
