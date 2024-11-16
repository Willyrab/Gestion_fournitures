<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Default Title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('head')  
</head>
@yield('custom-css')

<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <div class="p-4 pt-5">
                <a href="#" class="img logo rounded-circle mb-5"
                    style="background-image: url({{ asset('images/IMG_3499.png') }});">
                </a>
                <ul class="list-unstyled components mb-5">
                    <li class="active">
                        <a href="">
                            <span class="fa fa-home mr-3" style="margin-right: 15px;"></span>
                            CHome
                        </a>
                    </li>
                    <li class="active">

                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <span class="fa fa-pencil-alt mr-3"></span>Articles
                        </a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li>
                                <a href="{{route('insert_article.manager')}}"><span
                                        class="fa fa-plus-square mr-3"></span>Nouveau Article</a>
                            </li>
                            <li>
                                <a href="{{route('articles.list')}}"><span class="fa fa-list mr-3"></span>Liste des
                                    Articles</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="">
                            <span class="fa fa-user" style="margin-right: 15px;"></span>
                            Chiffre d'affaires
                        </a>
                    </li>
                    <li>
                        <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <span class="fa fa-cogs" style="margin-right: 15px;"></span>Fournisseurs
                        </a>
                        <ul class="collapse list-unstyled" id="pageSubmenu">
                            <li>
                                <a href="{{ route('insert_fournisseur.manager') }}">
                                    <span class="fa fa-user-plus mr-3"></span>Nouveau Fournisseurs
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('fournisseurs.list') }}">
                                    <span class="fa fa-list mr-3"></span>Liste des Fournisseurs
                                </a>
                            </li>
                        </ul>
                    </li>


                    <!-- New Mouvement List -->
                    <li>
                        <a href="#mouvementSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="fa fa-exchange-alt mr-3"></span>Mouvement
                        </a>
                        <ul class="collapse list-unstyled" id="mouvementSubmenu">
                            <li>
                                <a href="{{ route('entree.article') }}"><span
                                        class="fa fa-arrow-circle-down mr-3"></span>Entrée Articles</a>
                            </li>
                            <li>
                                <a href=""><span class="fa fa-arrow-circle-up mr-3"></span>Sortie Articles</a>
                            </li>
                        </ul>
                    </li>

                    <!-- New Historique List -->
                    <li>
                        <a href="#historiqueSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="fa fa-history mr-3"></span>Historique
                        </a>
                        <ul class="collapse list-unstyled" id="historiqueSubmenu">
                            <li>
                                <a href=""><span class="fa fa-arrow-down mr-3"></span>Mouvement Entrée</a>
                            </li>
                            <li>
                                <a href="{"><span class="fa fa-arrow-up mr-3"></span>Mouvement Sortie</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href=""><span class="fa fa-paper-plane" style="margin-right: 15px;"></span>Réinitialisation
                            base
                            de données</a>
                    </li>
                    <li>
                        <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="fa fa-sign-out mr-3" style="margin-right: 15px;"></span> Sign Out
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout.manager') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>

                <div class="footer">
                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;
                        <script>document.write(new Date().getFullYear());</script> All rights reserved | This template
                        is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com"
                            target="_blank">andrianirina@gmail.com</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </p>
                </div>

            </div>
        </nav>
        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <!-- Bouton qui affiche la première lettre du nom de l'utilisateur -->

                    <button type="button" id="sidebarCollapse" class="btn btn-primary"
                        style="width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 24px; background-color: #EB9D06; color: #fff;">
                        @if(Auth::guard('manager')->check())
                            {{ strtoupper(substr(Auth::guard('manager')->user()->nom_gestionnaire, 0, 1)) }}
                        @endif
                    </button>

                    @if(Auth::guard('manager')->check())
                        <div class="ml-3">
                            <p class="mb-0">Email: {{ Auth::guard('manager')->user()->email }}</p>
                        </div>
                    @endif


                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        @if(Auth::guard('manager')->check())
                            <div class="d-flex align-items-center">
                                <i class="fa fa-briefcase" style="font-size: 24px; margin-right: 10px; color: #EB9D06;"></i>
                                <!-- Icône du poste -->
                                <h5 class="mb-0">{{ Auth::guard('manager')->user()->poste->nom_poste }}</h5>
                                <!-- Nom du poste -->
                            </div>
                        @endif
                    </div>

                </div>
            </nav>

            @yield('content')
        </div>
    </div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')

</body>

</html>