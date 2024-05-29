@extends('layouts.master')

@section('title', 'Ruta')
@auth
@section('scripts')
@vite('resources/js/ruta.js')
@endsection
@endauth

@guest
@section('scripts')
@vite('resources/js/rutaGuest.js')
@endsection

@endguest
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fs-2">{{$ruta->nom_ruta}} {{$ruta->dificultat}}</h1>
        </div>
    </div>
    <hr class="mb-4">
    <div class="row">
        <div class="col-md-6 col-12">
            <p>{!! $ruta->ruta !!}</p>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header justify-content-between d-flex align-items-center">
                    <div>
                        <a href="{{route('perfil', $user->id)}}" class="text-reset text-decoration-none">
                            <img src="{{$user->avatar}}" alt="avatar" class="rounded-circle border" style="width: 50px; height: 50px;" draggable="false">
                            <span>{{$user->username}}</span>
                        </a>
                    </div>
                    <div>
                        <button class="btn rounded-circle border-dark p-0 ps-1 pe-1 text-center  " type="button"><i class="bi bi-three-dots"></i><span class="visually-hidden">Opcions</span></button>
                    </div>
                </div>
                <div id="comentaris" class="card-body comments">
                    @foreach($comentaris as $comentari)
                    <div class="d-flex mb-4">
                        <div class="d-flex w-100">
                            <a href="{{route('perfil', $comentari->user->id)}}" class="text-reset text-decoration-none">
                                <img src="{{$comentari->user->avatar}}" alt="avatar" class="rounded-circle me-1 border" style="width: 40px; height: 40px;" draggable="false">
                            </a>
                            <div class="d-flex w-100 flex-column">
                                <div class="d-flex align-items-baseline">
                                    <a href="{{route('perfil', $comentari->user->id)}}" class="text-reset text-decoration-none">
                                        <span class="fw-bold">{{$comentari->user->username}}</span>
                                    </a>
                                    <p class="m-0 ms-2 small" id="comentari-{{$comentari->id}}">{!! $comentari->comentari !!}</p>
                                </div>
                                <div id="info-{{$comentari->id}}" class="d-flex">
                                    <span class="text-muted small me-2"> {{$comentari->created}}</span>
                                    @if($comentari->editat)
                                    <span id="editat-{{$comentari->id}}" class="text-muted small me-2">Editat fa {{$comentari->edited}}</span>

                                    @endif
                                    @auth
                                    <button class="btn p-0 border-0 text-muted" id="{{$comentari->id}}" name="respondre" data-username="{{$comentari->user->username}}" type="button"><span class="small align-top">Respondre</span></button>
                                    @if(Auth::user()->id === $comentari->user_id)
                                    <!-- Button per obrir modal -->
                                    <button class="btn p-0 border-0 ms-2 text-muted" name="" type="button" data-bs-toggle="modal" data-bs-target="#editarComentari-{{$comentari->id}}"><i class="bi bi-three-dots"></i></button>
                                    @endif
                                    @endauth
                                </div>

                                <div id="divRespostes-{{$comentari->id}}" class="d-flex mt-2 @if($comentari->respostes->count() <= 0) d-none @endif">
                                    <button class="btn p-0 border-0 text-muted" id="veure-respostes-{{$comentari->id}}" name="respostes" type="button" data-bs-toggle="collapse" data-bs-target="#respostes-{{$comentari->id}}" aria-expanded="false" aria-controls="collapseExample">
                                        <span class="small align-top">Veure respostes <i class="bi bi-caret-down-fill"></i></span>
                                    </button>
                                </div>
                                <div class="collapse" id="respostes-{{$comentari->id}}">
                                    @foreach($comentari->respostes as $resposta)
                                    <div id="comentari-{{$resposta->id}}" class="d-flex mb-4">
                                        <a href="{{route('perfil', $resposta->user->id)}}" class="text-reset text-decoration-none">
                                            <img src="{{$resposta->user->avatar}}" alt="avatar" class="rounded-circle me-1 border" style="width: 40px; height: 40px;" draggable="false">
                                        </a>
                                        <div class="d-flex w-100 flex-column">
                                            <div class="d-flex align-items-baseline">
                                                <a href="{{route('perfil', $resposta->user->id)}}" class="text-reset text-decoration-none">
                                                    <span class="fw-bold">{{$resposta->user->username}}</span>
                                                </a>
                                                <p class="m-0 ms-2 small ">{!! $resposta->comentari !!}</p>
                                            </div>
                                            <div class="d-flex">
                                                <span class="text-muted small me-2"> {{$resposta->created}}</span>
                                                @auth
                                                <button class="btn p-0 border-0 text-muted" id="{{$resposta->id}}" name="respondre" data-username="{{$resposta->user->username}}" type="button"><span class="small align-top">Respondre</span></button>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>




                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal per editar el teu comentari -->
                    @auth
                    @if(Auth::user()->id === $comentari->user_id)
                    <div class="modal fade" id="editarComentari-{{$comentari->id}}" tabindex="-1" aria-labelledby="modalAvatarLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header ">
                                    <h4 class="modal-title m-auto" id="modalAvatarLabel">Editar Comentari</h4>

                                </div>
                                <div class="modal-body p-0">
                                    <button id="btnEditarComentari" type="button" class="btn rounded-0  user-hover w-100 border border-bottom border-top pt-3 pb-3 fs-5 p-1 text-center text-primary" data-bs-toggle="collapse" data-bs-target="#editar-{{$comentari->id}}" aria-expanded="false" aria-controls="collapseExample">Editar comentari</button>
                                    <div id="editar-{{$comentari->id}}" class="collapse justify-content-center">
                                        <form method="POST" name="formEditarComentari" class="d-flex ">
                                            @csrf
                                            <input type="hidden" name="comentariId" value="{{$comentari->id}}">
                                            <input type="text" name="comentari" class="form-control rounded-0" value="{{$comentari->comentari}}">
                                            <button id="btnEditarComentari" type="submit" class="btn btn-primary rounded-0 text-center ">Editar</button>
                                        </form>
                                    </div>

                                    <form method="POST" name="formEliminarComentari">
                                        @csrf
                                        <input type="hidden" name="comentariId" value="{{$comentari->id}}">
                                        <button id="btnEliminarAvatar" type="submit" class="btn rounded-0 text-danger w-100 border border-bottom user-hover pt-3 pb-3 fs-5 p-1 text-center mt-0">Eliminar comentari</button>
                                    </form>

                                    <button type="button" class="btn rounded-top-0 w-100 border border-bottom user-hover pt-3 pb-3 fs-5 p-1 text-center mt-0" data-bs-dismiss="modal" aria-label="Close">Cancel·lar</button>

                                </div>

                            </div>
                        </div>
                    </div>

                    @endif
                    @endauth

                    @endforeach

                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        <div class="d-flex">
                            <p class="m-0 text-break">{{$user->username}}: {{$ruta->descripcio}}</p>

                        </div>

                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            @guest
                            <button id="like" class="btn me-2 p-0 border-0  disabled" disabled><i class="bi fs-5 bi-heart"></i></button>
                            @endguest
                            @auth
                            <button id="like" class="btn me-2 p-0 border-0"><i class="bi fs-5 @if($liked) bi-heart-fill text-danger  @else bi-heart @endif"></i></button>
                            @endauth
                            <button id="compartir" class="btn me-2 p-0 border-0 @guest disabled @endguest" @guest disabled @endguest><i class="bi fs-5 bi-share"></i></button>
                        </div>
                        <div class="d-flex">
                            <span id="totalEscalat">{{$ruta->escalada}} </span>
                            @guest
                            <btn id="escalat" class="btn p-0 align-items-center  border-0 @guest disabled @endguest" @guest disabled @endguest><img src="{{asset('img/climb.svg')}}" class="" alt="escalada" style="width: 23px; height: 23px;"></btn>
                            @endguest
                            @auth
                            <btn id="escalat" class="btn p-0 align-items-center border-0 @if(!$escalat) opacity-50 @endif"><img src="{{asset('img/climb.svg')}}" class="" alt="escalada" style="width: 23px; height: 23px;"></btn>
                            @endauth
                        </div>
                    </div>
                    <div class="d-flex">
                        <span><span id="totalLikes">{{$ruta->likes}}</span> likes</span>
                    </div>
                    <div class="d-flex">
                        <span class="">Creat fa {{ $ruta->created }}</span>
                    </div>

                    <div class="d-flex">
                        <form id="formComentari" action="" method="post" class="w-100">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="inputComentari" name="comentari" class="form-control @guest disabled @endguest" @guest disabled @endguest placeholder="@auth Comenta... @endauth @guest Inicia sessió per comentar @endguest">
                                <button type="submit" class="btn btn-primary @guest disabled @endguest" @guest disabled @endguest><i class="bi bi-send"></i></button>
                            </div>
                            <input id="rutaId" type="hidden" name="rutaId" value="{{$ruta->id}}">
                            <input id="comentariId" type="hidden" name="comentariId" value="">
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@auth
<div class="modal fade" id="editarComentariModal" tabindex="-1" aria-labelledby="modalAvatarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header ">
                <h4 class="modal-title m-auto" id="modalAvatarLabel">Editar Comentari</h4>

            </div>
            <div class="modal-body p-0">
                <button id="btnEditarComentari" type="button" class="btn rounded-0  user-hover w-100 border border-bottom border-top pt-3 pb-3 fs-5 p-1 text-center text-primary" data-bs-toggle="collapse" data-bs-target="#editarComentari" aria-expanded="false" aria-controls="collapseExample">Editar comentari</button>
                <div id="editarComentari" class="collapse justify-content-center">
                    <form method="POST" name="formEditarComentari" class="d-flex ">
                        @csrf
                        <input type="hidden" id="editarComentariId" name="comentariId" value="">
                        <input type="text" id="editarComentariInput" name="comentari" class="form-control rounded-0" >
                        <button id="btnEditarComentari" type="submit" class="btn btn-primary rounded-0 text-center ">Editar</button>
                    </form>
                </div>

                <form method="POST" name="formEliminarComentari">
                    @csrf
                    <input type="hidden" id="eliminarComentariId" name="comentariId" value="">
                    <button id="btnEliminarAvatar" type="submit" class="btn rounded-0 text-danger w-100 border border-bottom user-hover pt-3 pb-3 fs-5 p-1 text-center mt-0">Eliminar comentari</button>
                </form>

                <button type="button" class="btn rounded-top-0 w-100 border border-bottom user-hover pt-3 pb-3 fs-5 p-1 text-center mt-0" data-bs-dismiss="modal" aria-label="Close">Cancel·lar</button>

            </div>

        </div>
    </div>
</div>
@endauth


@endsection