<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title')</title>
    @include('partials.styles')
    @include('partials.scripts')
    @yield('styles')
    @yield('scripts')

</head>

<body class="bg-body">
    <div id="body">

        <div class="shell">

            <div id="left" class="fixed-start">
                @include('partials.sidebar')
            </div>
            <div id="mid" style="min-width: 0;">

                @yield('content')

                <div id="divToasts" class="toast-container position-fixed top-5 start-5 p-3">
                    @if (session('success'))
                    <div class=" toast show text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true" autohide="true" >
                        <div class="toast-header">
                            <strong class="me-auto">Notificació</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close">
                        </div>
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
                @endif

                @if (session('error'))
                <div class=" toast show align-items-center text-white bg-primary border-0 bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">KilterZone</strong>
                        <small>now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close">
                    </div>
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                </div>

                @endif



            </div>
        </div>

    </div>
    </div>
    </div>
</body>

</html>