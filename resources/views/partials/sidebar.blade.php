<div class="border border-end min-vh-100">
    <div class="p-2 d-flex flex-column min-vh-100">
        <div class="d-flex justify-content-center mt-3 mb-4 align-items-center">
            <img src="{{ asset('KilterzoneLogo.png') }}" height="50px" width="50px" alt="logo de la web" />
            <span class=" fs-4 d-none ms-1 d-sm-inline text-center align-items-center text-light"><span>KilterZone</span></span>
        </div>
        <ul class="nav nav-pills text-start flex-column">
            <li class="nav-item py-2 py-sm-0 user-hover ">
                <a class="nav-link text-light " href="{{route('home')}}">
                    <i class="bi bi-house fs-4"></i>
                    <span class="fs-6 ms-1  d-sm-inline d-none">Inici</span>
                </a>
            </li>
            <li class="nav-item py-2 py-sm-0 user-hover">
                <a class="nav-link  text-light" href="#">
                    <i class="bi bi-search fs-4"></i>
                    <span class="fs-6 ms-1 d-none d-sm-inline">Buscar</span>
                </a>
            </li>
            <li id="liUsuaris" class="nav-item py-2 py-sm-0 user-hover">
                <button class="nav-link position-relative text-start w-100 h-100  text-light " data-bs-toggle="offcanvas" href="#offcanvasUsuaris" role="button" aria-controls="offcanvasUsuaris">
                    <i class="bi bi-person fs-4"></i>
                    <span class="fs-6 ms-1 d-none d-sm-inline">Usuaris</span>
                    @auth
                    <span name="solAmicsBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" @if ($totalFriendRequests == 0) style="display: none;" @endif>
                        <span name="solAmicBadgeValue"> {{$totalFriendRequests }}</span>
                        <span class="visually-hidden">Sol·licituds d'amistat</span>
                    </span>
                    @endauth
                </button>
            </li>
            @auth
            <li id="liXat" class="nav-item py-2 py-sm-0 user-hover">
                <button class="nav-link position-relative w-100 h-100 text-start text-light" data-bs-toggle="offcanvas" href="#offcanvasChat" role="button" aria-controls="offcanvasChat">
                    <i class="bi bi-chat-left fs-4"></i>
                    <span class="fs-6 ms-1 d-none d-sm-inline">Xat i Amics</span>
                    @auth
                    <span id="notificacionsBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" @if ($totalUnreadMessages==0) style="display: none;" @endif>
                        {{ $totalUnreadMessages }}
                        <span class="visually-hidden">Missatges sense llegir</span>
                    </span>
                    @endauth
                </button>
            </li>
            @endauth
            @guest
            <li class="nav-item py-2 py-sm-0 user-hover">
                <a class="nav-link  text-light" href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right fs-4"></i>
                    <span class="fs-6 ms-1 d-none d-sm-inline">Inicia Sessió</span>
                </a>
            </li>
            <li class="nav-item py-2 py-sm-0 user-hover">
                <a class="nav-link  text-light" href="{{ route('registre') }}">
                    <i class="bi bi-person-plus fs-4"></i>
                    <span class="fs-6 ms-1 d-none d-sm-inline">Registra't</span>
                </a>
            </li>
            @endguest
        </ul>
        @auth
        <input type="hidden" id="userId" value="{{ Auth::user()->id }}">

        <ul class="navbar-nav ms-1 text-bottom mt-auto mb-1 p-1 align-items-bottom user-hover text-light">
            <li class="nav-item dropdown no-arrow">
                <div class="nav-item dropdown no-arrow">
                    <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                        <img id="user_avatar" class="border bg-light rounded-circle" height="40px" width="40px" src="{{ Auth::user()->avatar }}" alt="avatar del usuari" />
                        <span class="d-none d-lg-inline me-2 text-gray-600 small">{{ Auth::user()->username }}
                        </span>

                    </a>
                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">

                        <a class="dropdown-item" href="{{ route('configuracio') }}">
                            <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Configuració
                        </a>
                        <a class="dropdown-item" href="{{ route('perfilPropi') }}">
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

    </div>
</div>

@include('partials.chat')
@include('partials.user')