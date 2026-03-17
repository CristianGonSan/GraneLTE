<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header border-0 p-0" wire:ignore>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab-general" role="tab">
                            General
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-extra" role="tab">
                            Referencia y adjunto
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body tab-content">
                <div class="tab-pane fade show active" id="tab-general" role="tabpanel" wire:ignore.self>
                    <div class="form-row">

                        <x-adminlte-input fgroup-class="col-md-6" name="effective_at" label="Fecha efectiva *"
                            type="datetime-local" wire:model="effective_at" required />

                        <x-form.select-wire-ignore fgroup-class="col-md-6" name="responsible_id" label="Responsable"
                            wire:loading.attr="readonly" wire:target="save" />

                        <x-adminlte-textarea fgroup-class="col-md-12" name="description" label="Descripción"
                            placeholder="Descripción del ajuste" wire:model="description" rows="2"
                            maxlength="255" />

                    </div>
                </div>

                <div class="tab-pane fade" id="tab-extra" role="tabpanel" wire:ignore.self>
                    <div class="form-row">

                        <x-adminlte-input fgroup-class="col-md-6" name="reference_type" label="Tipo de referencia"
                            placeholder="Motivo, folio, etc." type="text" wire:model="reference_type"
                            maxlength="32" />

                        <x-adminlte-input fgroup-class="col-md-6" name="reference_number" label="Número de referencia"
                            placeholder="Número de referencia" type="text" wire:model="reference_number"
                            maxlength="128" />

                        <x-livewire.file-upload name="attachment" label="Archivo adjunto" fgroup-class="col-md-12"
                            accept=".pdf,.jpg,.jpeg,.png,.webp" hint="PDF, JPG, PNG o WEBP. Máximo 10 MB.">
                            {{ $attachment?->getClientOriginalName() ?? 'Seleccionar archivo' }}
                        </x-livewire.file-upload>

                    </div>
                </div>
            </div>
        </div>

        <h2 class="h5">Lista de existencias</h2>

        <div x-on:keydown.enter.prevent>
            @include('partials.livewire.inventory.raw-material-documents.adjustments.adjustment-lines')
        </div>

        <div class="mb-3">
            <div class="mb-3">
                <x-checkbox name="isDraft" label="Guardar como borrador" title="Guarda este documento como borrador"
                    wire:model="isDraft" />
            </div>

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
