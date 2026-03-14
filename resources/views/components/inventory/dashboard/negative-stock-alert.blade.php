@if ($count > 0)
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fas fa-exclamation-circle mr-1"></i>
        <strong>Anomalía detectada.</strong>
        {{ $count }} stocks negativos.
        <a href="{{ route('raw-material-stocks.index', ['filter' => 'negative']) }}" target="_blank" class="ml-2">
            Revisar<i class="fas fa-external-link-alt ml-1"></i>
        </a>
    </div>
@endif
