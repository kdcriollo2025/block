@extends('adminlte::page')

@section('title', 'Médicos')

@section('content_header')
    <h1>Gestión de Médicos</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('admin.medicos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Médico
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter-specialty">Filtrar por Especialidad</label>
                        <select id="filter-specialty" class="form-control">
                            <option value="">Todas las especialidades</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter-status">Filtrar por Estado</label>
                        <select id="filter-status" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter-patients">Filtrar por N° de Pacientes</label>
                        <select id="filter-patients" class="form-control">
                            <option value="">Todos</option>
                            <option value="0">Sin pacientes</option>
                            <option value="1-10">1-10 pacientes</option>
                            <option value="11-20">11-20 pacientes</option>
                            <option value="20+">Más de 20 pacientes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">Búsqueda General</label>
                        <input type="text" id="search" class="form-control" placeholder="Buscar...">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="medicos-table" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Pacientes</th>
                            <th>Consultas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $patient)
                            <tr>
                                <td>{{ $patient->id }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->specialty }}</td>
                                <td>{{ $patient->phone_number }}</td>
                                <td>
                                    <span class="badge badge-{{ $patient->is_active ? 'success' : 'danger' }}">
                                        {{ $patient->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>{{ $patient->patients->count() }}</td>
                                <td>{{ $patient->medicalConsultations->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.medicos.edit', $patient->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.medicos.toggle-status', $patient->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $patient->is_active ? 'danger' : 'success' }} btn-sm" 
                                                    title="{{ $patient->is_active ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $patient->is_active ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No hay médicos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#medicos-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-info',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }
                    }
                ]
            });

            // Llenar el select de especialidades
            var specialties = [];
            table.column(3).data().each(function(value) {
                if (!specialties.includes(value)) {
                    specialties.push(value);
                }
            });
            specialties.sort().forEach(function(specialty) {
                $('#filter-specialty').append('<option value="' + specialty + '">' + specialty + '</option>');
            });

            // Filtro por especialidad
            $('#filter-specialty').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // Filtro por estado
            $('#filter-status').on('change', function() {
                table.column(5).search(this.value).draw();
            });

            // Filtro por número de pacientes
            $('#filter-patients').on('change', function() {
                var val = this.value;
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (val === '') return true;
                    
                    var patients = parseInt(data[6]);
                    switch(val) {
                        case '0': return patients === 0;
                        case '1-10': return patients >= 1 && patients <= 10;
                        case '11-20': return patients >= 11 && patients <= 20;
                        case '20+': return patients > 20;
                        default: return true;
                    }
                });
                table.draw();
                $.fn.dataTable.ext.search.pop();
            });

            // Búsqueda general
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@stop 