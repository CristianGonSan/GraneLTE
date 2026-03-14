@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title', 'Dashboard de Inventario')

@section('css')
    <style>
        #metrics,
        #pending-documents,
        #critical-alerts,
        #movement-trend,
        #document-costs,
        #cost-by-material,
        #cost-by-category,
        #cost-by-warehouse,
        #cost-by-supplier,
        #negative-stocks {
            scroll-margin-top: 57px;
        }
    </style>
@stop

@section('content_header')
    <h1 class="m-0">Dashboard de Inventario</h1>
@stop

@section('content')
    <div class="row">

        {{-- Metricas generales --}}
        <div id="metrics" class="col-12">
            <x-Inventory.Dashboard.SmallBoxMetrics />
        </div>

        {{-- Alert de stocks negativos --}}
        <div id="negative-stocks" class="col-12">
            <x-Inventory.Dashboard.NegativeStockAlert />
        </div>

        {{-- Documentos pendientes de aprobacion --}}
        <div id="pending-documents" class="col-12">
            <x-Inventory.Dashboard.PendingDocumentsTable />
        </div>

        {{-- Alertas criticas: vencimientos proximos y materiales con stock bajo --}}
        <div id="critical-alerts" class="col-12 mb-3">
            <div class="card-deck">
                <x-Inventory.Dashboard.BatchExpirationTable />
                <x-Inventory.Dashboard.LowStockMaterialsTable />
            </div>
        </div>

        {{-- Tendencia de movimientos en el tiempo --}}
        <div id="movement-trend" class="col-12">
            <x-Inventory.Dashboard.MovementTrendChart />
        </div>

        {{-- Movimientos de materiales en el tiempo --}}
        <div id="material-activity" class="col-12">
            <x-Inventory.Dashboard.MaterialActivityChart />
        </div>

        {{-- Costos por documento --}}
        <div id="document-costs" class="col-12">
            <x-Inventory.Dashboard.DocumentCostInfoBoxes />
        </div>

        {{-- Distribucion de costos por material y categoria --}}
        <div id="cost-by-material" class="col-md-6">
            <x-Inventory.Dashboard.CostByMaterialChart />
        </div>

        <div id="cost-by-category" class="col-md-6">
            <x-Inventory.Dashboard.CostByCategoryChart />
        </div>

        {{-- Distribucion de costos por almacen --}}
        <div id="cost-by-warehouse" class="col-md-6">
            <x-Inventory.Dashboard.CostByWarehouseChart />
        </div>

        <div id="cost-by-supplier" class="col-md-6">
            <x-Inventory.Dashboard.SupplierCostChart />
        </div>

    </div>
@stop
