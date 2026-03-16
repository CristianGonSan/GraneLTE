<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-3" name="effective_at" label="Fecha efectiva *" type="datetime-local"
                    wire:model="effective_at" required />

                <x-adminlte-input fgroup-class="col-md-3" name="reference_type" label="Tipo de referencia"
                    placeholder="Factura, pedido, etc." type="text" wire:model="reference_type" maxlength="32" />

                <x-adminlte-input fgroup-class="col-md-3" name="reference_number" label="Número de referencia"
                    placeholder="Número de referencia" type="text" wire:model="reference_number" maxlength="128" />

                <x-form.select-wire-ignore fgroup-class="col-md-3" name="responsible_id" label="Responsable"
                    wire:loading.attr="readonly" wire:target="save">
                </x-form.select-wire-ignore>

                <x-adminlte-textarea fgroup-class="col-md-12" name="description" label="Descripción"
                    placeholder="Descripción de la transacción" wire:model="description" rows="2"
                    maxlength="255" />

                {{-- Adjunto --}}
                <x-livewire.file-upload name="attachment" label="Archivo adjunto" fgroup-class="col-md-6 col-sm-12 mb-1"
                    accept=".pdf,.jpg,.jpeg,.png,.webp" hint="PDF, JPG, PNG o WEBP. Máximo 10 MB.">
                    @if ($attachment)
                        {{ $attachment->getClientOriginalName() }}
                    @else
                        {{ $this->document()->getAttachment()?->file_name ?? 'Seleccionar archivo' }}
                    @endif
                </x-livewire.file-upload>
            </div>
        </div>

        <h2 class="h5">Lista de existencias</h2>

        <div x-on:keydown.enter.prevent>
            @include('partials.livewire.inventory.raw-material-documents.issues.issue-lines')
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type="submit" label="Actualizar documento" class="mr-1" />

            <a href="{{ route('raw-material-documents.issues.show', ['document' => $documentId]) }}"
                class="btn btn-outline-secondary" wire:loading.attr="disabled">
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

            const responsibleSelect = select2Builder.selector('#responsible_id').wireModel('responsible_id')
                .value(@json($responsible_id), @json($responsibleText))
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
                }).build();
        });
    </script>
@endpush
