<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala de Telemedicina</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #f8f9fc;
        }
        #call-container {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="call-container"></div>
    <script crossorigin src="https://unpkg.com/@daily-co/daily-js"></script>
    <script>
        (function() {
            var container = document.getElementById('call-container');
            if(!container) return;

            var callFrame = window.DailyIframe.createFrame(
                container, {
                    showLeaveButton: true,
                    showFullscreenButton: true,
                    enable_pip_ui: true,
                    lang: 'es',
                    iframeStyle: {
                        width: '100%',
                        height: '100%',
                        border: '0',
                    }
                }
            );

            callFrame.join({ url: '{{ $teleconsultation->daily_room_url }}' }).catch(function(e) {
                console.error("Error uniendo a sala:", e);
                alert("Ocurrió un error al cargar la sala de videollamada.");
            });

            callFrame.on('joined-meeting', (event) => {
                @if($role == 'doctor')
                    callFrame.startRecording().then(() => {
                        console.log("Grabación en la nube iniciada.");
                    }).catch((err) => {
                        console.error("Error al iniciar grabación:", err);
                    });
                @endif
            });
        })();
    </script>
</body>
</html>
