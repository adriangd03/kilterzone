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
                    <div class="d-flex ">
                        <div class="d-flex col-md col-sm-12">

                            <div id="climbingBoard" class="climbing-board">
                                <!-- add svg -->
                                @include('partials.kilterboard')


                            </div>
                        </div>
                        <div class="d-flex col">
                            <form id="formCrearRuta" action="">
                                <div class="row mb-3">

                                    <div class="col">
                                        <label for="layout" class="form-label">Layout</label>
                                        <select class="form-select" id="layout" name="layout" required>
                                            <option value="homeWall">Kilter Board Home Wall</option>
                                            <option value="original">Kilter Board Original</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="size" class="form-label">Mida de la paret</label>
                                        <select class="form-select" id="size" name="size" required>
                                            <option value="7x10FullRideLedKit">7x10 Full ride LED Kit</option>
                                            <option value="7x10MainlineLedKit">7x10 Mainline LED Kit</option>
                                            <option value="7x10AuxliaryLedKit">7x10 Auxliary LED Kit</option>
                                            <option value="10x10FullRideLedKit">10x10 Full ride LED Kit</option>
                                            <option value="10x10MainlineLedKit">10x10 Mainline LED Kit</option>
                                            <option value="10x10AuxliaryLedKit">10x10 Auxliary LED Kit</option>
                                            <option value="8x12FullrideLedKit">8x12 Full ride LED Kit</option>
                                            <option value="8x12MainlineLedKit">8x12 Mainline LED Kit</option>
                                            <option value="10x12FullrideLedKit">10x12 Full ride LED Kit</option>
                                            <option value="10x12MainlineLedKit">10x12 Mainline LED Kit</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-center">
                                    <div class="form-check me-2">

                                        <input id="mainline" type="checkbox" name="mainline" value="1" class="form-check-input" checked="checked">
                                        <label for="mainline" class="form-check-label">Mainline</label>
                                    </div>
                                    <div class="form-check">
                                        <input id="auxiliary" type="checkbox" name="auxiliary" value="2" class="form-check-input">
                                        <label for="auxiliary" class="form-check-label">Auxiliary</label>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom de la ruta</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="row">

                                    <div class="col">
                                        <label for="dificultat" class="form-label">Grau de la ruta</label>
                                        <select class="form-select" id="dificultat" name="dificultat" required>
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
                                    </div>
                                    <div class="col">
                                        <label for="inclinacio" class="form-label">Inclinació de la paret</label>
                                        <select class="form-select" id="inclinacio" name="inclinacio" required>
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
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcio" class="form-label">Descripció de la ruta</label>
                                    <textarea class="form-control" id="descripcio" name="descripcio" rows="3" required></textarea>
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