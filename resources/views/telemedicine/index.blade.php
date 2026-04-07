@extends('layouts.master-layouts')
@section('title') Telemedicina @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">💻 Mis Teleconsultas</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            @if($role != 'doctor')
                            <th>Doctor</th>
                            @endif
                            <th>Fecha y Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teleconsultations as $tel)
                        <tr>
                            <td>#{{ $tel->id }}</td>
                            <td>{{ $tel->appointment->patient->first_name ?? '' }} {{ $tel->appointment->patient->last_name ?? '' }}</td>
                            @if($role != 'doctor')
                            <td>{{ $tel->appointment->doctor->first_name ?? '' }} {{ $tel->appointment->doctor->last_name ?? '' }}</td>
                            @endif
                            <td>
                                {{ $tel->appointment->appointment_date }} <br>
                                <small>{{ $tel->appointment->timeSlot->from ?? '' }} - {{ $tel->appointment->timeSlot->to ?? '' }}</small>
                            </td>
                            <td>
                                @if($tel->status == 'pending')
                                    <span class="badge bg-warning">Pendiente</span>
                                @elseif($tel->status == 'in-progress')
                                    <span class="badge bg-primary">En Curso</span>
                                @else
                                    <span class="badge bg-success">Completado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('telemedicine.room', $tel->id) }}" class="btn btn-primary btn-sm waves-effect waves-light">
                                        <i class="bx bx-video"></i> Unirse a la sala
                                    </a>
                                    <button type="button" class="btn btn-info btn-sm waves-effect waves-light" onclick="copyToClipboard('{{ $tel->daily_room_url }}')">
                                        <i class="bx bx-share-alt"></i> Compartir Link
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        });

        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(function() {
                alert("¡Link copiado exitosamente al portapapeles!\nYa puedes enviárselo al paciente.");
            }, function(err) {
                console.error('Error al copiar link: ', err);
                alert("No se pudo copiar el link. Cópialo manualmente: " + url);
            });
        }
    </script>
@endsection
