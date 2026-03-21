@extends('adminlte::page')

@section('title_prefix', 'Acerca del sistema |')

@section('content_header')
    <h1 class="m-0">Acerca del sistema</h1>
@stop

@section('css')
    <style>
        section {
            scroll-margin-top: 57px;
        }

        .sticky-top {
            top: 57px;
        }

        .link-subtle {
            color: inherit;
            text-decoration: underline;
            text-decoration-color: rgba(0, 0, 0, 0.25);
            text-underline-offset: 2px;
        }

        .link-subtle:hover {
            color: inherit;
            text-decoration-color: rgba(0, 0, 0, 0.6);
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-9">

            {{-- DESCRIPCIÓN GENERAL --}}
            <section id="description" class="py-3">
                <h5 class="font-weight-bold">GraneLTE</h5>
                <p>
                    Sistema interno de gestión de inventario a granel con seguimiento por lotes, control de
                    existencias y registro de movimientos mediante documentos de operación. No es un SaaS;
                    está diseñado para un único entorno industrial que requiere trazabilidad completa de
                    materias primas, desde su recepción hasta su consumo o transferencia entre almacenes.
                </p>
                <p class="mb-0">
                    Cada operación queda respaldada por un documento con flujo de aprobación formal,
                    garantizando integridad y auditoría de los movimientos de inventario.
                </p>
            </section>

            <hr>

            {{-- ÁREAS Y MÓDULOS --}}
            <section id="areas" class="py-3">
                <h5 class="font-weight-bold">Áreas y módulos</h5>
                <p>
                    El sistema se organiza en seis áreas funcionales. Cada área agrupa pantallas y
                    operaciones relacionadas, y su acceso está controlado por permisos de rol.
                </p>

                <h6 class="font-weight-bold mt-3">Administración</h6>
                <p>
                    Gestión de usuarios del sistema y roles con asignación de permisos granulares por
                    acción. Permite crear, editar y desactivar usuarios, así como definir qué operaciones
                    puede realizar cada rol.
                </p>

                <h6 class="font-weight-bold mt-3">Catálogos</h6>
                <p>
                    Tablas maestras que sirven de base al resto del sistema: unidades de medida,
                    categorías, almacenes, proveedores, responsables y materias primas. Deben
                    configurarse antes de registrar cualquier documento.
                </p>

                <h6 class="font-weight-bold mt-3">Documentos de operación</h6>
                <p>
                    Módulo central del sistema. Registra las operaciones de inventario mediante cuatro
                    tipos de documento: entradas, salidas, transferencias y ajustes. Cada documento pasa
                    por un flujo de aprobación antes de impactar las existencias
                    (ver sección <em>Flujo de documentos</em>).
                </p>

                <h6 class="font-weight-bold mt-3">Movimientos</h6>
                <p>
                    Historial cronológico de todas las transacciones generadas por documentos aceptados.
                    Es de solo lectura y permite auditar cualquier cambio en el inventario.
                </p>

                <h6 class="font-weight-bold mt-3">Lotes</h6>
                <p>
                    Seguimiento por número de lote con trazabilidad completa de entradas y consumos.
                    Permite ubicar en qué almacén está un lote y cuánta cantidad queda disponible.
                </p>

                <h6 class="font-weight-bold mt-3">Existencias</h6>
                <p class="mb-0">
                    Stock actual por lote de materia prima y almacén, calculado a partir del acumulado de
                    movimientos registrados. Se actualiza automáticamente al aceptar un documento.
                </p>
            </section>

            <hr>

            {{-- FLUJO DE DOCUMENTOS --}}
            <section id="flow" class="py-3">
                <h5 class="font-weight-bold">Flujo de documentos</h5>
                <p>
                    Todos los documentos de operación comparten el mismo ciclo de vida,
                    independientemente de su tipo:
                </p>

                {{-- Diagrama --}}
                <div class="d-flex flex-wrap align-items-center bg-light border rounded p-3 my-3" style="gap:.75rem">
                    <div class="border rounded p-2 bg-white text-center" style="min-width:110px">
                        <div class="font-weight-bold small">Borrador</div>
                        <div class="text-muted" style="font-size:.75rem">Editable y eliminable</div>
                    </div>
                    <i class="fas fa-arrow-right text-muted"></i>
                    <div class="border rounded p-2 bg-white text-center" style="min-width:110px">
                        <div class="font-weight-bold small">Pendiente</div>
                        <div class="text-muted" style="font-size:.75rem">En revisión</div>
                    </div>
                    <i class="fas fa-arrow-right text-muted"></i>
                    <div class="d-flex flex-column" style="gap:.5rem">
                        <div class="border rounded p-2 bg-white text-center" style="min-width:110px">
                            <div class="font-weight-bold small">Aceptado</div>
                            <div class="text-muted" style="font-size:.75rem">Genera movimientos</div>
                        </div>
                        <div class="border rounded p-2 bg-white text-center" style="min-width:110px">
                            <div class="font-weight-bold small">Rechazado</div>
                            <div class="text-muted" style="font-size:.75rem">Sin efecto</div>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-muted"></i>
                    <div class="d-flex flex-column" style="gap:.5rem">
                        <div class="border rounded p-2 bg-white text-center" style="min-width:110px">
                            <div class="font-weight-bold small">Cancelado</div>
                            <div class="text-muted" style="font-size:.75rem">Indica la anulación de un documento</div>
                        </div>
                    </div>
                </div>

                <p>
                    Un documento en estado <strong>Borrador</strong> puede editarse y eliminarse
                    libremente. Al enviarse pasa a <strong>Pendiente</strong>, donde queda bloqueado para
                    edición. El revisor puede <strong>Aceptarlo</strong>, lo que genera los movimientos
                    correspondientes e impacta existencias y lotes, o <strong>Rechazarlo</strong>, en cuyo
                    caso el documento queda cerrado sin ningún efecto sobre el inventario.
                </p>

                <h6 class="font-weight-bold mt-3 text-muted">Tipos de documento</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Entrada</dt>
                    <dd class="col-sm-9">Recepción de materia prima desde un proveedor. Incrementa las existencias en el
                        almacén destino y crea o incrementa el lote correspondiente.</dd>

                    <dt class="col-sm-3">Salida</dt>
                    <dd class="col-sm-9">Consumo o despacho de materia prima. Decrementa las existencias del almacén origen
                        y descuenta del lote indicado.</dd>

                    <dt class="col-sm-3">Transferencia</dt>
                    <dd class="col-sm-9">Traslado entre dos almacenes. Genera un movimiento de salida en el origen y uno de
                        entrada en el destino.</dd>

                    <dt class="col-sm-3 mb-0">Ajuste</dt>
                    <dd class="col-sm-9 mb-0">Corrección manual de stock, positiva o negativa. Queda registrado como
                        movimiento de ajuste para efectos de auditoría.</dd>
                </dl>
            </section>

            <hr>

            {{-- CONVENCIONES DE UI --}}
            <section id="ui" class="py-3">
                <h5 class="font-weight-bold">Convenciones de la interfaz</h5>

                <h6 class="font-weight-bold mt-3">Campos obligatorios</h6>
                <p>
                    Los campos requeridos se identifican con un asterisco <code>*</code> ubicado fuera
                    y a continuación del texto del label. El asterisco no forma parte del nombre del
                    campo; es un indicador visual independiente.
                </p>
                <div class="bg-light border rounded p-3 mb-3">
                    <div class="form-group mb-2">
                        <label class="mb-1">Materia prima <span class="text-muted font-weight-bold">*</span></label>
                        <input type="text" class="form-control form-control-sm" placeholder="Seleccionar…" disabled>
                    </div>
                    <div class="form-group mb-0">
                        <label class="mb-1">Observaciones</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Opcional" disabled>
                    </div>
                </div>

                <h6 class="font-weight-bold mt-3">Errores de validación</h6>
                <p>
                    Cuando un campo no pasa la validación, su borde se resalta y aparece un mensaje
                    descriptivo debajo. Esto ocurre al intentar guardar y en tiempo real al modificar
                    el campo (validación con Livewire).
                </p>
                <div class="bg-light border rounded p-3 mb-3">
                    <div class="form-group mb-0">
                        <label class="mb-1">Cantidad <span class="text-muted font-weight-bold">*</span></label>
                        <input type="text" class="form-control form-control-sm is-invalid" value="abc" disabled>
                        <div class="invalid-feedback d-block">El campo debe ser un número mayor a cero.</div>
                    </div>
                </div>

                <h6 class="font-weight-bold mt-3">Notificaciones (Toast)</h6>
                <p>
                    Las confirmaciones de acciones exitosas, advertencias y errores se muestran como
                    notificaciones emergentes en la esquina superior derecha. Desaparecen automáticamente
                    y no interrumpen el flujo de trabajo.
                </p>

                <div>
                    <button class="btn btn-outline-success mr-1" onclick="exampleSuccess()">
                        <i class="fas fa-fw fa-check-circle mr-1"></i>Éxito
                    </button>
                    <button class="btn btn-outline-warning mr-1" onclick="exampleWarning()">
                        <i class="fas fa-fw fa-exclamation-triangle mr-1"></i>Advertencia
                    </button>
                    <button class="btn btn-outline-danger" onclick="exampleError()">
                        <i class="fas fa-fw fa-times-circle mr-1"></i>Error
                    </button>
                </div>

                <h6 class="font-weight-bold mt-3">Confirmaciones</h6>
                <p>
                    Las acciones irreversibles (eliminar, aceptar o rechazar un documento) requieren
                    confirmación explícita antes de ejecutarse mediante un modal con descripción de la
                    acción y opciones de confirmar o cancelar.
                </p>

                <div>
                    <button class="btn btn-outline-primary" onclick="exampleConfirm()">
                        <i class="fas fa-fw fa-save mr-1"></i>Confirmación
                    </button>
                </div>

                <h6 class="font-weight-bold mt-3">Control de permisos</h6>
                <p class="mb-0">
                    Los botones, opciones de menú y rutas se muestran u ocultan según los permisos del
                    rol del usuario. Acceder directamente a una ruta sin permiso redirige a una pantalla
                    de acceso denegado (403). Los permisos siguen el patrón <code>recurso.acción</code>,
                    por ejemplo: <code>raw-materials.view</code>, <code>raw-material-documents.edit</code>.
                </p>
            </section>

            <hr>

            {{-- STACK TECNOLÓGICO --}}

            <section id="stack" class="py-3">
                <h5 class="font-weight-bold">Stack tecnológico</h5>
                <p>El sistema está construido sobre el siguiente conjunto de tecnologías:</p>
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Tecnología</th>
                            <th>Versión</th>
                            <th>Rol en el sistema</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="https://www.php.net" target="_blank" rel="noopener" class="link-subtle">PHP</a>
                            </td>
                            <td>8.3+</td>
                            <td>Lenguaje base del servidor</td>
                        </tr>
                        <tr>
                            <td><a href="https://laravel.com" target="_blank" rel="noopener"
                                    class="link-subtle">Laravel</a></td>
                            <td>11.x</td>
                            <td>Framework backend — rutas, autenticación, ORM y lógica de negocio</td>
                        </tr>
                        <tr>
                            <td><a href="https://livewire.laravel.com" target="_blank" rel="noopener"
                                    class="link-subtle">Livewire</a></td>
                            <td>3.x</td>
                            <td>Componentes dinámicos full-stack sin JavaScript personalizado</td>
                        </tr>
                        <tr>
                            <td><a href="https://spatie.be/docs/laravel-permission" target="_blank" rel="noopener"
                                    class="link-subtle">Spatie Permission</a></td>
                            <td>6.x</td>
                            <td>Gestión de roles y permisos de usuario</td>
                        </tr>
                        <tr>
                            <td><a href="https://spatie.be/docs/laravel-medialibrary" target="_blank" rel="noopener"
                                    class="link-subtle">Spatie MediaLibrary</a></td>
                            <td>11.x</td>
                            <td>Gestión y almacenamiento de archivos adjuntos y medios</td>
                        </tr>
                        <tr>
                            <td><a href="https://laravel-excel.com" target="_blank" rel="noopener"
                                    class="link-subtle">Maatwebsite Excel</a></td>
                            <td>3.x</td>
                            <td>Importación y exportación de archivos Excel / CSV</td>
                        </tr>
                        <tr>
                            <td><a href="https://jeroennoten.github.io/Laravel-AdminLTE" target="_blank" rel="noopener"
                                    class="link-subtle">AdminLTE</a></td>
                            <td>3.x</td>
                            <td>Plantilla de administración — layout, componentes y estilos base</td>
                        </tr>
                        <tr>
                            <td><a href="https://getbootstrap.com" target="_blank" rel="noopener"
                                    class="link-subtle">Bootstrap</a></td>
                            <td>4.x</td>
                            <td>Grid, utilidades CSS y componentes de interfaz</td>
                        </tr>
                        <tr>
                            <td><a href="https://fontawesome.com" target="_blank" rel="noopener"
                                    class="link-subtle">Font Awesome</a></td>
                            <td>6.x</td>
                            <td>Iconografía</td>
                        </tr>
                        <tr>
                            <td><a href="https://select2.org" target="_blank" rel="noopener"
                                    class="link-subtle">Select2</a></td>
                            <td>4.x</td>
                            <td>Selectores con búsqueda y carga AJAX para catálogos</td>
                        </tr>
                        <tr>
                            <td><a href="https://sweetalert2.github.io" target="_blank" rel="noopener"
                                    class="link-subtle">SweetAlert 2</a></td>
                            <td>11.x</td>
                            <td>Modales de confirmación y alertas</td>
                        </tr>
                        <tr>
                            <td><a href="https://www.mysql.com" target="_blank" rel="noopener"
                                    class="link-subtle">MySQL</a></td>
                            <td>8.x</td>
                            <td>Base de datos relacional</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <hr>

            {{-- AUTOR Y CONTACTO --}}
            <section id="autor" class="py-3">
                <h5 class="font-weight-bold">Autor y contacto</h5>
                <p>
                    Este sistema fue desarrollado por <strong>Cristian González Santos</strong>. Para reportar
                    errores o solicitar mejoras, utiliza los canales indicados a continuación.
                </p>
                <ul class="list-unstyled mb-3">
                    <li class="mb-1">
                        <i class="fas fa-envelope fa-fw mr-2 text-muted"></i>
                        <a href="mailto:cristiangonsan18@gmail.com">cristiangonsan18@gmail.com</a>
                    </li>
                    <li>
                        <i class="fab fa-github fa-fw mr-2 text-muted"></i>
                        <a href="https://github.com/CristianGonSan/GraneLTE" target="_blank" rel="noopener noreferrer">
                            https://github.com/CristianGonSan/GraneLTE
                        </a>
                    </li>
                </ul>
                <p class="text-muted small mb-0">
                    GraneLTE es un sistema interno de uso exclusivo. © {{ date('Y') }}
                </p>
            </section>

        </div>

        {{-- Índice lateral --}}
        <div class="col-lg-3 d-none d-lg-block">
            <nav class="sticky-top pt-3 pl-3 border-left">
                <p class="text-uppercase text-muted font-weight-bold" style="font-size:.75rem; letter-spacing:.05em">
                    En esta página
                </p>
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="#description" class="text-secondary">Descripción general</a></li>
                    <li class="mb-1"><a href="#areas" class="text-secondary">Áreas y módulos</a></li>
                    <li class="mb-1"><a href="#flow" class="text-secondary">Flujo de documentos</a></li>
                    <li class="mb-1"><a href="#ui" class="text-secondary">Convenciones de la interfaz</a></li>
                    <li class="mb-1"><a href="#stack" class="text-secondary">Stack tecnológico</a></li>
                    <li class="mb-1"><a href="#autor" class="text-secondary">Autor y contacto</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        function exampleSuccess() {
            Toast.fire({
                icon: 'success',
                title: 'Documento guardado correctamente.'
            });
        }

        function exampleWarning() {
            Toast.fire({
                icon: 'warning',
                title: 'El documento ya fue enviado a revisión.'
            });
        }

        function exampleError() {
            Toast.fire({
                icon: 'error',
                title: 'No tienes permiso para esta acción.'
            });
        }

        function exampleConfirm() {
            Swal.fire({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Registro eliminado correctamente.'
                    });
                }
            });
        }
    </script>
@endsection
