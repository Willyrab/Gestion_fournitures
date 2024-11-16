<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Default Title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header_departement.css') }}">
    @yield('head')

</head>

@yield('custom-css')

<body>
    <!-- Top Info Bar -->
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            @if(Auth::guard('departement')->check())
                <div class="info-text">
                    <i class="fa fa-envelope"></i> Email: {{ Auth::guard('departement')->user()->email }}
                </div>
            @endif
            @if(Auth::guard('departement')->check())
                    <div class="info-text">
                    @if(Auth::guard('departement')->user()->postes->nom_departement == 'Informatique')
                        <i class="fa fa-laptop"></i>
                    @elseif(Auth::guard('departement')->user()->postes->nom_departement == 'Ressources Humaines')
                        <i class="fa fa-users"></i>
                    @else
                        <i class="fa fa-building"></i>
                    @endif
                    {{ Auth::guard('departement')->user()->postes->nom_departement }}
                </div>
            @endif
    </div>
    </div>

    <!-- Wrapper for flexible layout -->
    <div class="wrapper d-flex align-items-stretch">

        <!-- Main Content Area -->
        <div id="content" class="p-4 p-md-5">

            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-dark shadow-sm">
                <div class="container-fluid d-flex justify-content-between">

                    <!-- Logo on the left -->
                    <a class="navbar-brand" href="#">
                        <img src="{{ asset('images/IMG_3499.png') }}" alt="Logo" class="rounded-circle">
                    </a>

                    <!-- Left-side Navbar Links -->
                    <ul class="navbar-nav d-none d-lg-flex">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('historique*') ? 'active' : '' }}"
                                href="#" id="demandesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                Demandes
                            </a>
                            <div class="dropdown-menu" aria-labelledby="demandesDropdown">
                                <a class="dropdown-item" href="{{ route('Form_besoins.departement') }}">
                                    Demande article
                                </a>
                                <a class="dropdown-item" href="{{ route('nouveau_article.departement') }}">
                                    Nouveau article
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="historiqueDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Historiques
                            </a>
                            <div class="dropdown-menu" aria-labelledby="historiqueDropdown">
                                <a class="dropdown-item" href="{{ route('liste_besoins.departement')}}">
                                    Demande article
                                </a>
                                <a class="dropdown-item" href="{{ route('liste_NDarticle.departement')}}">
                                    Liste des nouveaux articles
                                </a>
                            </div>
                        </li>
                    </ul>

                    <!-- Right-side Navbar Links -->
                    <ul class="navbar-nav d-none d-lg-flex">
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt mr-1"></i> Se Déconnecter
                            </a>
                        </li>
                        <!-- Hidden Logout Form -->
                        <form id="logout-form" action="{{ route('logout.departement') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </nav>

            <!-- Page Specific Content -->
            <div class="mt-4">
                @yield('content')
            </div>

        </div> <!-- End of content -->
    </div> <!-- End of wrapper -->
    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')

</body>

</html>