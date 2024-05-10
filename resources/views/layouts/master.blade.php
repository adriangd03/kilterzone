<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @include('partials.styles')
    @include('partials.scripts')
    @yield('styles')
    @yield('scripts')

</head>

<body class="bg-body ">

    <div class="row p-0 ">
        <div class="col-12 p-0 ">

            <div class="row flex-nowrap p-0">

                @include('partials.sidebar')
                <div class="position-relative">

                    @yield('content')

                    <div aria-live="polite" aria-atomic="true" class="position-relative m-5">
                        <div id="divToasts" class="toast-container top-5 start-5 p-3">


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>