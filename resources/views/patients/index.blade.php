@extends('adminlte::page')

@section('title', 'Pacientes')

@section('content_header')
    <h1>Gestión de Pacientes</h1>
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

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('medico.patients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Paciente
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filter-gender">Filtrar por Género</label>
                        <select id="filter-gender" class="form-control">
                            <option value="">Todos los géneros</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filter-consultations">Filtrar por N° de Consultas</label>
                        <select id="filter-consultations" class="form-control">
                            <option value="">Todos</option>
                            <option value="0">Sin consultas</option>
                            <option value="1-5">1-5 consultas</option>
                            <option value="6-10">6-10 consultas</option>
                            <option value="10+">Más de 10 consultas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">Búsqueda General</label>
                        <input type="text" id="search" class="form-control" placeholder="Buscar...">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="patients-table" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Género</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Tipo de Sangre</th>
                            <th>Consultas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr>
                                <td>{{ $patient->id }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone }}</td>
                                <td>{{ $patient->gender }}</td>
                                <td>{{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $patient->blood_type ?? 'N/A' }}</td>
                                <td>{{ $patient->medicalConsultations ? $patient->medicalConsultations->count() : 0 }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('medico.patients.show', $patient->id) }}" class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('medico.patients.edit', $patient->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($patient->medicalHistory)
                                            <a href="{{ route('medico.medical_histories.show', $patient->medicalHistory->id) }}" 
                                               class="btn btn-primary btn-sm" title="Historial Médico">
                                                <i class="fas fa-file-medical"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
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
            var table = $('#patients-table').DataTable({
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
                ],
                columnDefs: [
                    {
                        targets: [8], // La columna de acciones
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Filtro por género
            $('#filter-gender').on('change', function() {
                table.column(4).search(this.value).draw();
            });

            // Filtro por número de consultas
            $('#filter-consultations').on('change', function() {
                var val = this.value;
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (val === '') return true;
                    
                    var consultations = parseInt(data[7]); // Índice correcto para la columna de consultas
                    switch(val) {
                        case '0': return consultations === 0;
                        case '1-5': return consultations >= 1 && consultations <= 5;
                        case '6-10': return consultations >= 6 && consultations <= 10;
                        case '10+': return consultations > 10;
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
