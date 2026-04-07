@extends('layouts.master-layouts')
@section('title') Sala de Telemedicina @endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📹 Consulta de Telemedicina</h4>
            <div class="page-title-right">
                <a href="{{ route('telemedicine.index') }}" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back"></i> Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center" style="height: 70vh;">
        <!-- Daily Prebuilt IFrame Container -->
        <div id="call-container" style="width: 100%; height: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
    </div>
</div>

@endsection

@section('script')
    <script crossorigin src="https://unpkg.com/@daily-co/daily-js"></script>
    <script>
        // Use an IIFE or just inline since scripts are at bottom
        (function() {
            var container = document.getElementById('call-container');
            if(!container) return;

            var callFrame = window.DailyIframe.createFrame(
                container, {
                    showLeaveButton: true,
                    iframeStyle: {
                        width: '100%',
                        height: '100%',
                        border: '0',
                    }
                }
            );

            callFrame.join({ url: '{{ $teleconsultation->daily_room_url }}' }).catch(function(e) {
                console.error("Error uniendo a sala:", e);
                alert("Ocurrió un error al cargar la sala de videollamada. Revisa la consola.");
            });

            callFrame.on('left-meeting', (event) => {
                window.location.href = "{{ route('telemedicine.index') }}";
            });
        })();
    </script>
@endsection
