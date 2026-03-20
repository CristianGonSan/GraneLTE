@php
    use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus as Status;
    $status = $document->status;
@endphp

<div>
    <div class="card mb-3">
        <div class="card-body">
            <strong>
                {{ $status->label() }}
            </strong>
            <div class="text-muted">
                {{ $status->description() }}
            </div>

            @if ($val = $document->validator)
                <hr>
                <div>
                    {{ $status->label() }} por
                    @can('users.view')
                        <a href="{{ route('users.show', $val->id) }}" target="_blank">
                            {{ $val->name }}
                        </a>
                    @else
                        {{ $val->name }}
                    @endcan
                    el {{ $document->validated_at->format('d/m/Y - h:i a') }}
                </div>
            @endif
        </div>
    </div>

    <div class="mb-3">
        @switch($status)
            @case(Status::DRAFT)
                @if ($document->created_by === auth()->id())
                    @can('raw-material-documents.edit')
                        <a href="{{ $document->getRoute('edit') }}" class="btn btn-outline-warning mr-1">
                            <i class="fas fa-fw fa-edit"></i> Editar
                        </a>
                    @endcan

                    @can('raw-material-documents.delete')
                        <x-livewire.loading-button label="Eliminar" icon="trash-alt" theme="outline-danger" class="mr-1"
                            wire:click='delete' wire:swal-confirm="¿Eliminar este borrador?" swal-icon="warning" />
                    @endcan

                    <x-livewire.loading-button label="Pasar a pendiente" theme="outline-primary" icon="clock" class="mr-1"
                        wire:click="changeStatus('{{ Status::PENDING }}')"
                        wire:swal-confirm="¿Seguro que deseas pasar el documento a Pendiente?" />
                @endif
            @break

            @case(Status::PENDING)
                @can('raw-material-documents.accept')
                    <x-livewire.loading-button label="Aceptar" theme="outline-success" class="mr-1" icon="circle-check"
                        wire:click="changeStatus('{{ Status::ACCEPTED }}')" wire:swal-confirm="¿Aceptar este documento?" />
                @endcan

                @can('raw-material-documents.reject')
                    <x-livewire.loading-button label="Rechazar" theme="outline-danger" class="mr-1" icon="circle-xmark"
                        wire:click="changeStatus('{{ Status::REJECTED }}')" wire:swal-confirm="¿Rechazar este documento?" />
                @endcan
            @break

            @case(Status::ACCEPTED)
                @can('raw-material-documents.cancel')
                    <x-livewire.loading-button label="Cancelar" theme="outline-danger" class="mr-1" icon="ban"
                        wire:click="changeStatus('{{ Status::CANCELED }}')" wire:swal-confirm="¿Cancelar el documento aceptado?" />
                @endcan
            @break

        @endswitch

        <a href="{{ route('raw-material-documents.index') }}" class="btn btn-outline-secondary mr-1">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>

</div>
