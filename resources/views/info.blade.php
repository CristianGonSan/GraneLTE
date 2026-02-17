@extends('adminlte::page')

@section('title', 'Acerca del Sistema')

@section('content_header')
    <h1>Acerca del Sistema</h1>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">

            <!-- 1. Presentación del sistema -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">1. Presentación</h3>
                </div>
                <div class="card-body">
                    <p>
                        El presente sistema ha sido desarrollado para la <strong>gestión integral de inventarios</strong> de
                        materias primas a granel destinadas a la fabricación de alimento para ganado.
                    </p>

                    <p>
                        Su propósito principal es proporcionar información confiable y en tiempo real sobre:
                    </p>

                    <ul>
                        <li>Existencias físicas por almacén y lote</li>
                        <li>Movimientos de entrada y salida</li>
                        <li>Histórico completo de transacciones</li>
                        <li>Trazabilidad de lotes y proveedores</li>
                    </ul>

                    <p class="mt-3">
                        <strong>Objetivos estratégicos</strong>
                    </p>
                    <ol>
                        <li>Minimizar mermas y pérdidas por manejo inadecuado</li>
                        <li>Optimizar el nivel de inventario y evitar faltantes</li>
                        <li>Garantizar la trazabilidad exigida por normas de calidad e inocuidad</li>
                        <li>Apoyar la planeación de compras y producción</li>
                    </ol>
                </div>
            </div>


            <!-- 2. Funcionalidades principales -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">2. Funcionalidades principales</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="far fa-check-circle text-success mr-2"></i>Control de inventario en tiempo
                                    real</li>
                                <li><i class="far fa-check-circle text-success mr-2"></i>Registro de entradas (remisiones /
                                    compras)</li>
                                <li><i class="far fa-check-circle text-success mr-2"></i>Registro de salidas (consumos /
                                    traspasos)</li>
                                <li><i class="far fa-check-circle text-success mr-2"></i>Gestión de lotes y caducidad</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="far fa-check-circle text-success mr-2"></i>Histórico detallado de movimientos
                                </li>
                                <li><i class="far fa-check-circle text-success mr-2"></i>Gestión de usuarios y permisos por
                                    rol</li>
                                <li><i class="far fa-check-circle text-success mr-2"></i>Reportes exportables (PDF / Excel)
                                </li>
                                <li><i class="far fa-check-circle text-success mr-2"></i>Alertas automáticas de stock mínimo
                                    / crítico</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <!-- 3. Estado actual del sistema -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">3. Estado actual del sistema</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th width="35%">Versión</th>
                                <td>1.0.0</td>
                            </tr>
                            <tr>
                                <th>Periodo de uso activo</th>
                                <td>Enero 2026</td>
                            </tr>
                            <tr>
                                <th>Entorno</th>
                                <td>Producción</td>
                            </tr>
                            <tr>
                                <th>Última actualización</th>
                                <td>Pendiente de definir</td>
                            </tr>
                            <tr>
                                <th>Motor de base de datos</th>
                                <td>MySQL 8.x</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- 4. Tecnologías empleadas -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">4. Tecnologías empleadas</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <i class="fab fa-laravel fa-3x text-danger mb-2"></i>
                            <div><strong>Laravel</strong></div>
                            <small>v11.x</small>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <i class="fab fa-php fa-3x text-primary mb-2"></i>
                            <div><strong>PHP</strong></div>
                            <small>8.3</small>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <i class="fas fa-database fa-3x text-success mb-2"></i>
                            <div><strong>MariaDB</strong></div>
                            <small>10.4.x</small>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <i class="fas fa-bell fa-3x text-info mb-2"></i>
                            <div><strong>SweetAlert2</strong></div>
                            <small>Notificaciones</small>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <i class="fab fa-bootstrap fa-3x text-info mb-2"></i>
                            <div><strong>AdminLTE 3</strong></div>
                            <small>Bootstrap 4</small>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <i class="fas fa-font-awesome-flag fa-3x text-secondary mb-2"></i>
                            <div><strong>Font Awesome 6</strong></div>
                            <small>Íconos</small>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- col-md-8 -->


        <div class="col-md-4">

            <!-- Usuario conectado -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Usuario conectado</h3>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-user-circle fa-5x text-secondary mb-3"></i>
                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-3 small">{{ Auth::user()->email ?? '—' }}</p>
                    <p class="small text-muted">
                        Puede ejecutar las acciones permitidas según su rol y permisos asignados.
                    </p>
                </div>
            </div>


            <!-- Notas importantes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Notas importantes</h3>
                </div>
                <div class="card-body">
                    <p class="font-weight-bold mb-3">
                        <span class="text-danger">*</span> Todos los campos marcados con asterisco son obligatorios.
                    </p>

                    <p>Esta pantalla puede utilizarse para comunicar:</p>
                    <ul class="mb-0">
                        <li>Avisos generales del sistema</li>
                        <li>Recordatorios operativos</li>
                        <li>Próximos mantenimientos</li>
                        <li>Información administrativa relevante</li>
                    </ul>
                </div>
            </div>

        </div><!-- col-md-4 -->
    </div>

@endsection

@section('footer')
    <div class="text-center text-muted small">
        <p class="mb-0">InventoryLTE</p>
        <p>Versión 1.0.0 · Enero 2026</p>
        <p>© {{ date('Y') }} — Todos los derechos reservados</p>
    </div>
@endsection
