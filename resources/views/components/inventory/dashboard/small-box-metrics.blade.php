<div class="row">
    {{-- Costo total en stock --}}
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalStockCost, 2) }}</h3>
                <p>Costo total en stock</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="#cost-by-material" class="small-box-footer">
                Ver más<i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    {{-- Materiales activos --}}
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $activeMaterials }}</h3>
                <p>Materiales activos</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="{{ route('raw-materials.index') }}" target="_blank" class="small-box-footer">
                Ver más<i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    {{-- Documentos pendientes --}}
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendingDocuments }}</h3>
                <p>Documentos pendientes</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="#pending-documents" class="small-box-footer">
                Ver más<i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    {{-- Materiales con stock bajo --}}
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $lowStockMaterials }}</h3>
                <p>Materiales con stock bajo</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="#critical-alerts" class="small-box-footer">
                Ver más<i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

</div>
