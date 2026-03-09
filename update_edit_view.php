<?php
$detailsPath = __DIR__ . '/resources/views/prescription/prescription-details.blade.php';
$editPath = __DIR__ . '/resources/views/prescription/prescription-edit.blade.php';

$content = file_get_contents($detailsPath);

// Title
$content = str_replace("@section('title', 'Crear Consulta')", "@section('title', 'Editar Consulta')", $content);

// Form
$content = str_replace('action="{{ route(\'prescription.store\') }}"', 'action="{{ url(\'prescription/\' . $prescription->id) }}"', $content);
$content = str_replace('@csrf', "@csrf\n<input type=\"hidden\" name=\"_method\" value=\"PATCH\" />\n<input type=\"hidden\" name=\"id\" value=\"{{ \$prescription->id }}\" id=\"form_id\" />", $content);

// Preload Patient and Appointment
$content = str_replace(
    'var preloadPatientId = \'{{ $preloadPatientId ?? \'\' }}\';',
    'var preloadPatientId = \'{{ $prescription->patient_id }}\';',
    $content
);
$content = str_replace(
    'var preloadAppointmentId = \'{{ $preloadAppointmentId ?? \'\' }}\';',
    'var preloadAppointmentId = \'{{ $prescription->appointment_id }}\';',
    $content
);

// Remove user id mapping
$content = str_replace(
    '<input type="hidden" name="created_by" value="{{ $user->id }}">',
    '',
    $content
);

// Fix "Consulta por" and "Diagnóstico" models variables
$content = str_replace(
    '<textarea name="consulta_por" class="form-control"></textarea>',
    '<textarea name="consulta_por" class="form-control">{{ old(\'consulta_por\', $prescription->consulta_por) }}</textarea>',
    $content
);
$content = str_replace(
    '<textarea name="diagnostico" class="form-control" rows="3" placeholder="Describa el diagnóstico..."></textarea>',
    '<textarea name="diagnostico" class="form-control" rows="3" placeholder="Describa el diagnóstico...">{{ old(\'diagnostico\', $prescription->diagnosis) }}</textarea>',
    $content
);

// Evaluacion.estudios_laboratorios and Evaluacion.medicamentos
// we need to get Evaluacion
$evaluacion_estudios = "{{ optional(\\App\\Evaluacion::where('prescription_id', \$prescription->id)->first())->estudios_laboratorios }}";
$evaluacion_medicamentos = "{{ optional(\\App\\Evaluacion::where('prescription_id', \$prescription->id)->first())->medicamentos }}";

$content = str_replace(
    '<textarea name="estudios_laboratorios" class="form-control" rows="3" placeholder="Exámenes a realizar..."></textarea>',
    '<textarea name="estudios_laboratorios" class="form-control" rows="3" placeholder="Exámenes a realizar...">' . $evaluacion_estudios . '</textarea>',
    $content
);

$content = str_replace(
    '<textarea name="tratamiento" id="tratamiento_texto" class="form-control" rows="6" placeholder="Escriba aquí todo el tratamiento, medicamentos, indicaciones y dosis recomendadas..."></textarea>',
    '<textarea name="tratamiento" id="tratamiento_texto" class="form-control" rows="6" placeholder="Escriba aquí todo el tratamiento, medicamentos, indicaciones y dosis recomendadas...">' . $evaluacion_medicamentos . '</textarea>',
    $content
);

// Botones y labels de guardado
$content = str_replace(
    '<i class="bx bx-save me-1 font-size-18 align-middle"></i> Crear Consulta',
    '<i class="bx bx-save me-1 font-size-18 align-middle"></i> Actualizar Consulta',
    $content
);


// Archivos (Existing ones to display)
$archivos_html = <<<'HTML'
                            @if(isset($prescription) && $prescription->archivos->count())
                                <hr>
                                <h5>Archivos Clínicos Guardados</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Archivo</th>
                                            <th>Observaciones</th>
                                            <th width="120">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prescription->archivos as $archivo)
                                            <tr>
                                                <td>
                                                    <a href="{{ asset('storage/'.$archivo->url_file) }}" target="_blank">
                                                        {{ basename($archivo->url_file) }}
                                                    </a>
                                                </td>
                                                <td>{{ $archivo->observaciones }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" onclick="if(confirm('¿Eliminar archivo?')){ document.getElementById('del-archivo-{{ $archivo->id }}').submit(); }" class="btn btn-danger btn-sm">Eliminar</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                @foreach($prescription->archivos as $archivo)
                                <form id="del-archivo-{{ $archivo->id }}" action="{{ route('archivo.destroy', $archivo->id) }}" method="POST" style="display:none;">
                                    @csrf @method('DELETE')
                                </form>
                                @endforeach
                            @endif
HTML;

$content = str_replace(
    "@include('prescription.partials.archivos')",
    $archivos_html . "\n<br/>" . "@include('prescription.partials.archivos')",
    $content
);


file_put_contents($editPath, $content);
echo "Edit view generated based on details view.\n";
