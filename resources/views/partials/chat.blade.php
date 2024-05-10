@auth
<!-- Offcanvas Chat -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasChat" data-bs-backdrop="false" aria-labelledby="offcanvasChatLabel">



    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Xat</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body h-100">
        <div class="row h-100 ">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Amics</h4>
                            </div>
                            <div class="card-body h-100 mh-100 ">
                                <div id="amics" class="friends-list row mh-100 p-3">
                                    @foreach($friends as $friend)
                                    <div class="friend col-3 p-2 justify-content-center position-relative text-center user-hover" id="{{ $friend->id }}">
                                        <div class="user-info">
                                            <img class="rounded-circle" src="{{$friend->avatar}}" alt="avatar 1" style="width: 45px; height: 100%;">
                                            <div class="card-text">
                                                <div class="friend-username">{{ $friend->username }}</div>
                                                <div id="status{{$friend->id}}" class="user-status">Offline</div>
                                            </div>
                                            <a href="{{route('perfil', $friend->id)}}" class="btn btn-primary">Visitar Perfil</a>
                                        </div>
                                        <span id="b-{{$friend->id}}" class="position-absolute top-0 start-100 translate-middle  badge rounded-pill bg-danger" @if($friend->unreadMessages == 0) style="display: none;" @endif >
                                            {{$friend->unreadMessages}}
                                            <span class="visually-hidden">Missatges sense llegir</span>
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card mb-5">
                    <div class="card-header">
                        <div class="row d-block">
                            <span>Xat</span>
                            <span id="chat-user-name" class="text-muted p-0"></span>
                            <span id="typing" class="text-muted p-0" style="display: none;">...escribint<span>

                        </div>
                    </div>
                    <div id="chat-user" class="card-body chat-list  overflow-auto ">

                    </div>
                    <div class="card-footer pe-5">
                        <form id='chatForm' method="post">
                            <div class="input-group">
                                <input type="text" id="message" name="message" class="form-control" placeholder="Escriu el teu missatge aquí...">
                                <input type="hidden" name="receiver" id="receiver" value="0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endauth