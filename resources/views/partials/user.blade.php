<!-- Offcanvas Chat -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUsuaris" data-bs-backdrop="false" aria-labelledby="offcanvasUsuarisLabel">



    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Usuaris</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body h-100">
        <div class="row h-100 ">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-12">

                        <ul class="nav nav-tabs" id="usersTab">

                            <li class="nav-item">
                                <a class="nav-link active" id="usuaris-tab" data-bs-toggle="tab" href="#usuaris" role="tab" aria-controls="usuaris" aria-selected="true">Usuaris</a>
                            </li>
                            @auth
                            <li class="nav-item position-relative">
                                <a class="nav-link" id="solAmics-tab" data-bs-toggle="tab" href="#solAmics" role="tab" aria-controls="solAmics" aria-selected="false">Sol·licituds d'amistat</a>

                                <span name="solAmicsBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" @if ($totalFriendRequests == 0) style="display: none;" @endif>
                                    <span name="solAmicBadgeValue"> {{$totalFriendRequests }}</span>
                                    <span class="visually-hidden">Sol·licituds d'amistat</span>
                                </span>
                            </li>
                            @endauth
                        </ul>
                        <div class="tab-content" id="usersTabContent">
                            <div class="tab-pane fade show active" id="usuaris" role="tabpanel" aria-labelledby="usuaris-tab">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h4>Usuaris</h4>
                                        <input type="text" class="form-control ms-auto w-25" id="searchUser" name="searchUser" placeholder="Cerca usuari">

                                    </div>
                                    <div class="card-body h-100 mh-100 ">
                                        <div id="users" class="users-list row mh-100 p-3">
                                            @foreach($notFriends as $user)  
                                            
                                            
                                            <a id="divNotFriend-{{ $user->id }}" href="{{ route('perfil', $user->id) }}" class=" text-decoration-none rounded user-hover">
                                                <div  class="row m-1 w-100 pt-2 pb-2 ">
                                                    <div class="col align-content-center">
                                                        <div class="d-flex d-inine align-content-center align-items-center">
                                                            <img class="rounded-circle" src="{{ $user->avatar }}" alt="avatar 1" style="width: 45px; height: 45px;">
                                                            <div class="card-text ms-2">
                                                                <div class="friend-username text-dark">{{ $user->username }}</div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @auth
                                                    <div class="col align-content-center">
                                                        @if ($user->sentFriendRequest)
                                                        <span class="btn btn-primary border border-white disabled">Sol·licitud enviada</span>
                                                        @else
                                                        <form name="formSolAmic" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="friend_id" value="{{ $user->id }}">
                                                            <button class="btn btn-primary border border-white" type="submit">Afegir amic</button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                    @endauth

                                                </div>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @auth
                            <div class="tab-pane fade" id="solAmics" role="tabpanel" aria-labelledby="solAmics-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Sol·licituds d'amistat</h4>
                                    </div>
                                    <div class="card-body h-100 mh-100 ">
                                        <div id="DivSolAmics" class="users-list  row mh-100 p-3">
                                            @foreach($friendRequests as $friendRequest)
                                            <div class="col-4" name="divSolAmic">
                                                <a href="{{ route('perfil', $friendRequest->user->id) }}" class="text-decoration-none">
                                                    <div id="solAmic-{{$friendRequest->user->id}}" class="col-12 text-center p-2  rounded user-hover ">
                                                        <div class="user-info">
                                                            <img class="rounded-circle" src="{{$friendRequest->user->avatar}}" alt="avatar 1" style="width: 45px; height: 45px;">
                                                            <div class="card-text">
                                                                <div class="fw-bold text-dark">{{ $friendRequest->user->username }}</div>
                                                            </div>
                                                            <form name="formAcceptarSolAmic" action="acceptarSolicitudAmic" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="friend_id" value="{{ $friendRequest->user->id }}">
                                                                <button class="btn btn-primary border border-white" type="submit">Acceptar</button>
                                                            </form>
                                                            <form name="formRebutjarSolAmic" action="rebutjarSolicitudAmic" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="friend_id" value="{{ $friendRequest->user->id }}">
                                                                <button class="btn btn-danger border border-white" type="submit">Rebutjar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            @endforeach
                                            @if (count($friendRequests) == 0)
                                            <div class="text-center fw-bold">
                                                No tens sol·licituds d'amistat
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endauth
                        </div>


                    </div>

                </div>



            </div>
        </div>
    </div>
</div>