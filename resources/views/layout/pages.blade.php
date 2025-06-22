<!DOCTYPE html>
<html lang="en" class="notranslate">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta translate="no">
    <title>@yield('title') </title>
    {{-- Assets File --}}
    <link href="{{ asset('assets/img/kemenag.png') }}" rel="icon">
    <link rel="stylesheet" href="{{ asset('dist/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/mix/app.css') }}">
    <script src="{{ asset('assets/mix/app.js') }}"></script>
    {{-- End Assets File --}}
</head>

<style>
    /* .navbar-bg {
        background: #010787 !important;
    } */
      .modal-backdrop {
            width: 0 !important;
        }
</style>
<body class="layout-3">
    <div id="app">
        <div class="main-wrapper container">
            @include('layout.nav_pages')
            @yield('content')
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#btn-fullscreen').on('click', function(e) {
                e.preventDefault();
                var icon = $('#fullscreen-icon');

                if (
                    !document.fullscreenElement &&
                    !document.webkitFullscreenElement &&
                    !document.msFullscreenElement
                ) {
                    var docEl = document.documentElement;

                    if (docEl.requestFullscreen) {
                        docEl.requestFullscreen();
                    } else if (docEl.webkitRequestFullscreen) {
                        docEl.webkitRequestFullscreen();
                    } else if (docEl.msRequestFullscreen) {
                        docEl.msRequestFullscreen();
                    }

                    icon.removeClass('fa-expand').addClass('fa-compress');
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }

                    icon.removeClass('fa-compress').addClass('fa-expand');
                }
            });
        });
    </script>

</body>

</html>
