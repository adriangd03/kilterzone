@extends('layouts.master')

@section('title', 'Crear ruta')

@section('scripts')
@vite('resources/js/crearRuta.js')
@endsection


@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h1>Crear ruta</h1>

            <hr class="mt-3 mb-3">

            <div class="card border-0">
                <div class="card-body">
                    <!-- div for the svg of the climbing board -->
                    <div class="d-flex row ">
                        <div class="d-flex col-md col-sm-12">

                            <div id="climbingBoard" class="svg-container ">
                                <!-- Afegim els svgs dels kilterboards -->
                                @include('partials.kilterboards.homewall.7x10-mainline-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.7x10-auxiliary-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.7x10-full-ride-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.10x10-mainline-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.10x10-auxiliary-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.8x12-full-ride-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.8x12-mainline-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.8x12-auxiliary-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.10x12-full-ride-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.10x12-mainline-led-kit-home-wall')
                                @include('partials.kilterboards.homewall.10x12-auxiliary-led-kit-home-wall')
                                @include('partials.kilterboards.original.16x12-boltons-screwons-original')
                                @include('partials.kilterboards.original.16x12-boltons-original')
                                @include('partials.kilterboards.original.16x12-screwons-original')
                                @include('partials.kilterboards.original.12x14-boltons-screwons-original')
                                @include('partials.kilterboards.original.12x14-boltons-original')
                                @include('partials.kilterboards.original.12x14-screwons-original')
                                @include('partials.kilterboards.original.12x12-boltons-screwons-original')
                                @include('partials.kilterboards.original.12x12-boltons-original')
                                @include('partials.kilterboards.original.12x12-screwons-original')
                                @include('partials.kilterboards.original.8x12-boltons-screwons-original')
                                @include('partials.kilterboards.original.8x12-boltons-original')
                                @include('partials.kilterboards.original.8x12-screwons-original')
                                @include('partials.kilterboards.original.7x10-boltons-screwons-original')
                                @include('partials.kilterboards.original.7x10-boltons-original')
                                @include('partials.kilterboards.original.7x10-screwons-original')



                            </div>
                        </div>
                        <div class="d-flex col-md col-sm-12">
                            <form id="formCrearRuta" action="">
                                <div class="row mb-3">

                                    <div class="col">
                                        <label for="layout" class="form-label">Layout</label>
                                        <select class="form-select" id="layout" name="layout">
                                            <option value="homeWall">Kilter Board Home Wall</option>
                                            <option value="original">Kilter Board Original</option>
                                        </select>
                                        <div class="d-none alert alert-danger mt-2" id="layoutAlert"></div>
                                    </div>
                                    <div class="col">
                                        <label for="size" class="form-label">Mida de la paret</label>
                                        <select class="form-select" id="size" name="size">
                                     

                                        </select>
                                        <div class="d-none alert alert-danger mt-2" id="sizeAlert"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom de la ruta</label>
                                    <input type="text" class="form-control" id="nom" name="nom">
                                    <div class="d-none alert alert-danger mt-2" id="nomAlert"></div>
                                </div>
                                <div class="row">

                                    <div class="col">
                                        <label for="dificultat" class="form-label">Grau de la ruta</label>
                                        <select class="form-select" id="dificultat" name="dificultat">
                                            <option value="4a">4a</option>
                                            <option value="4b">4b</option>
                                            <option value="4c">4c</option>
                                            <option value="5a">5a</option>
                                            <option value="5b">5b</option>
                                            <option value="5c">5c</option>
                                            <option value="6a">6a</option>
                                            <option value="6a">6a+</option>
                                            <option value="6b">6b</option>
                                            <option value="6b">6b+</option>
                                            <option value="6c">6c</option>
                                            <option value="6c">6c+</option>
                                            <option value="7a">7a</option>
                                            <option value="7a">7a+</option>
                                            <option value="7b">7b</option>
                                            <option value="7b">7b+</option>
                                            <option value="7c">7c</option>
                                            <option value="7c">7c+</option>
                                            <option value="8a">8a</option>
                                            <option value="8a">8a+</option>
                                            <option value="8b">8b</option>
                                            <option value="8b">8b+</option>
                                            <option value="8c">8c</option>
                                            <option value="8c">8c+</option>
                                        </select>
                                        <div class="d-none alert alert-danger mt-2" id="dificultatAlert"></div>
                                    </div>
                                    <div class="col">
                                        <label for="inclinacio" class="form-label">Inclinació de la paret</label>
                                        <select class="form-select" id="inclinacio" name="inclinacio">
                                            <option value="0º">0º</option>
                                            <option value="5º">5º</option>
                                            <option value="10º">10º</option>
                                            <option value="15º">15º</option>
                                            <option value="20º">20º</option>
                                            <option value="25º">25º</option>
                                            <option value="30º">30º</option>
                                            <option value="35º">35º</option>
                                            <option value="40º">40º</option>
                                            <option value="45º">45º</option>
                                            <option value="50º">50º</option>
                                            <option value="55º">55º</option>
                                            <option value="60º">60º</option>
                                            <option value="65º">65º</option>
                                            <option value="70º">70º</option>
                                        </select>
                                        <div class="d-none alert alert-danger mt-2" id="inclinacioAlert"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcio" class="form-label">Descripció de la ruta</label>
                                    <textarea class="form-control" id="descripcio" name="descripcio" rows="3"></textarea>
                                    <div class="d-none alert alert-danger mt-2" id="descripcioAlert"></div>
                                </div>
                                <div class="mb-3 form-check form-switch justify-content-center ">
                                    <input class="form-check-input" type="checkbox" role="switch" id="esborrany" name="esborrany">
                                    <label class="form-check-label" for="esborrany">Guardar com a esborrany</label>
                                </div>
                                <div class="row text-center">
                                    <button type="submit" class="btn btn-primary w-0 ">Crear ruta</button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection