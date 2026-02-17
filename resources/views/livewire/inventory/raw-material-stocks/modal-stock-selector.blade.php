<div x-data="{ open: @entangle('showModal') }">
    <template x-if="open">
        <div>
            <div class="modal-backdrop show"></div>
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" x-on:click.outside="open = false">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Seleccionar existencias</h5>
                            <button type="button" class="close" x-on:click="open = false">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <p class="text-muted text-center py-3">No hay información del stock.</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" x-on:click="open = false">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
