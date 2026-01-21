        @include('layouts.common-scripts');

        @yield('script')
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.min.js') }}"></script>
        <script>
            @if (Session::has('success'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.success("{{ session('success') }}");
            @endif
            @if (Session::has('error'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                }
                toastr.error("{{ session('error') }}");
            @endif
            @if (Sentinel::getUser())
                function notifyCount() {
                    var load_count = $('.badge-pill').html();
                    var token = $("input[name='_token']").val();
                    $.ajax({
                        type: "get",
                        url: "/notification-count",
                        data: {
                            _token: token,
                        },
                        success: function(data) {
                            $('.badge-pill').html(data);
                            if (load_count < data) {
                                notificationShow();
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
                setInterval(function() {
                    notifyCount();
                }, 10000);
            @endif
            @if (Sentinel::getUser())
                function notificationShow() {
                    var token = $("input[name='_token']").val();
                    $.ajax({
                        type: "POST",
                        url: "/top-notification",
                        data: {
                            _token: token,
                        },
                        success: function(data) {
                            $('.notification-list-scroll').html(data.options);
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
                setInterval(function() {
                    notificationShow();
                }, 5000);
            @endif
        </script>
        @yield('script-bottom')
