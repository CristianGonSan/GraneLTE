@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Exportar')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Exportar</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <h1 class="h4">Exportar</h1>

    <hr>

    <h2 class="h5">Exportar stock</h2>

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-data-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-materials-tab" data-toggle="pill" href="#pills-materials"
                    role="tab" aria-controls="pills-materials" aria-selected="true">
                    <i class="fas fa-fw fa-wheat-awn"></i>
                    <span class="d-none d-sm-inline ml-1">Materiales</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-stocks-tab" data-toggle="pill" href="#pills-stocks" role="tab"
                    aria-controls="pills-stocks" aria-selected="false">
                    <i class="fas fa-fw fa-boxes-stacked"></i>
                    <span class="d-none d-sm-inline ml-1">Existencias</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-batches-tab" data-toggle="pill" href="#pills-batches" role="tab"
                    aria-controls="pills-batches" aria-selected="false">
                    <i class="fas fa-fw fa-box"></i>
                    <span class="d-none d-sm-inline ml-1">Lotes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-movements-tab" data-toggle="pill" href="#pills-movements" role="tab"
                    aria-controls="pills-movements" aria-selected="false">
                    <i class="fas fa-fw fa-cart-flatbed"></i>
                    <span class="d-none d-sm-inline ml-1">Movimientos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-documents-tab" data-toggle="pill" href="#pills-documents" role="tab"
                    aria-controls="pills-documents" aria-selected="false">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span class="d-none d-sm-inline ml-1">Documentos</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-data-tabContent">
        <div class="tab-pane fade show active" id="pills-materials" role="tabpanel" aria-labelledby="pills-materials-tab">
            <livewire:Inventory.RawMaterials.ExportRawMaterials />
        </div>
        <div class="tab-pane fade" id="pills-stocks" role="tabpanel" aria-labelledby="pills-stocks-tab">
            <livewire:Inventory.RawMaterialStocks.ExportRawMaterialStocks />
        </div>
        <div class="tab-pane fade" id="pills-batches" role="tabpanel" aria-labelledby="pills-batches-tab">
            <livewire:Inventory.RawMaterialBatches.ExportRawMaterialBatches />
        </div>
        <div class="tab-pane fade" id="pills-movements" role="tabpanel" aria-labelledby="pills-movements-tab">
            <livewire:Inventory.RawMaterialMovements.ExportRawMaterialMovements />
        </div>
        <div class="tab-pane fade" id="pills-documents" role="tabpanel" aria-labelledby="pills-documents-tab">
            <livewire:Inventory.RawMaterialDocuments.ExportRawMaterialDocuments />
        </div>
    </div>

    <hr>

    <h2 class="h5">Exportar costos transversales</h2>

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-costs-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-warehouses-tab" data-toggle="pill" href="#pills-warehouses"
                    role="tab" aria-controls="pills-warehouses" aria-selected="true">
                    <i class="fas fa-fw fa-warehouse"></i>
                    <span class="d-none d-sm-inline ml-1">Almacenes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-categories-tab" data-toggle="pill" href="#pills-categories"
                    role="tab" aria-controls="pills-categories" aria-selected="false">
                    <i class="fas fa-fw fa-tags"></i>
                    <span class="d-none d-sm-inline ml-1">Categorías</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-costs-tabContent">
        <div class="tab-pane fade show active" id="pills-warehouses" role="tabpanel"
            aria-labelledby="pills-warehouses-tab">
            <livewire:Inventory.Warehouses.ExportWarehouseValuation />
        </div>
        <div class="tab-pane fade" id="pills-categories" role="tabpanel" aria-labelledby="pills-categories-tab">
            <livewire:Inventory.Categories.ExportCategoryValuation />
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Restaurar tabs al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            ['pills-data-tab', 'pills-costs-tab'].forEach(function(tabGroupId) {
                var savedTab = localStorage.getItem(tabGroupId);
                if (savedTab) {
                    var tabEl = document.querySelector('[href="' + savedTab + '"]');
                    if (tabEl) $(tabEl).tab('show');
                }
            });

            // Guardar tab activo al cambiar
            $('[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                var href = $(e.target).attr('href');
                // Detectar a qué grupo pertenece
                var group = $(e.target).closest('ul').attr('id');
                localStorage.setItem(group, href);
            });
        });
    </script>
@endpush
