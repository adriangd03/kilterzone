<header>
    <nav class="navbar navbar-dark navbar-expand bg-dark shadow mb-4 align-items-around topbar static-top ">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <span class="mx-auto">Pràctica 07</span>

            </a>
            <!-- barra navegació -->
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{route('home')}}">Home</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{route('home')}}">Rutes propies</a>
                    </li>


                    @endauth


                </ul>

            </div>

            <!-- button per obrir el xat, barra de busqueda i button per afegir amics-->
            <div class="d-flex align-items-center">

                @auth


                <!-- Dropdown dels usuaris no amics amb botons per afegir amic i visitar perfil-->
                <div class="dropdown-center" id="dropdownAddFriend" >
                    <button class="btn btn-dark border border-white dropdown position-relative" data-bs-toggle="dropdown" href="#dropdownAddFriend" role="button" aria-expanded="false" aria-controls="dropdownAddFriend">
                        <i class="bi bi-person-plus"></i>
                    </button>
                    <ul class="dropdown-menu overflow-auto" aria-labelledby="dropdownMenuButton">
                        @foreach($notFriends as $user)
                        <li>
                            <div class="dropdown-item">
                                <div class="d-flex justify-content-between ">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle" src="{{$user->avatar}}" alt="avatar 1" style="width: 45px; height: 100%;">
                                        <div class="ms-2">
                                            <div class="fw-bold">{{$user->username}}</div>
                                            <div>{{$user->email}}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <form method="POST">
                                            @csrf
                                            <input type="hidden" name="friendId" value="{{$user->id}}">
                                            <button class="btn btn-dark border border-white" type="submit">Afegir amic</button>
                                        </form>
                                        <a class="btn btn-dark border border-white">Visitar perfil</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <button class="btn btn-dark border ms-3 border-white position-relative" data-bs-toggle="offcanvas" href="#offcanvasChat" role="button" aria-controls="offcanvasChat">
                    @if($totalUnreadMessages == 0)
                    <span id="notificacionsBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                        {{ $totalUnreadMessages }}
                        <span class="visually-hidden">Missatges sense llegir</span>
                    </span>
                    @else
                    <span id="notificacionsBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $totalUnreadMessages }}
                        <span class="visually-hidden">Missatges sense llegir</span>
                    </span>
                    @endif

                    <i class="bi bi-chat-left-dots"></i>
                </button>
                <input type="hidden" id="userId" value="{{ Auth::user()->id }}">

                @endauth

                <form class="d-none d-sm-inline-block me-3 ms-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Cerca..." aria-label="Search" aria-describedby="basic-addon2">
                        <button class="btn btn-dark border border-white" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>


            @auth
            <!-- Mostrar avatar i nom de l'usuari -->
            <ul class="navbar-nav flex-nowrap ms-auto">
                <li class="nav-item dropdown no-arrow">
                    <div class="nav-item dropdown no-arrow">
                        <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                            <span class="d-none d-lg-inline me-2 text-gray-600 small">{{ Auth::user()->username }} </span>

                            <img id="user_avatar" class="border bg-light rounded-circle img-profile" height="40px" width="40px" src="{{ (Auth::user()->avatar)}}" alt="avatar del usuari" />
                        </a>
                        <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">

                            <a class="dropdown-item" href="{{route('home')}}">
                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Perfil
                            </a>
                            <a class="dropdown-item" href="#">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="btn me-2 text-gray-400" type="submit">Logout</button>
                                </form>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
            @endauth
            @guest
            <!-- Mostrar dos botons per el login i registre -->
            <ul class="navbar-nav flex-nowrap ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('registre') }}">Registre</a>
                </li>
            </ul>
            @endguest
        </div>

    </nav>
</header>