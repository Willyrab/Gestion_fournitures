<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Default Title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
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

                    <li class="{{ request()->routeIs('index.manager') ? 'active' : '' }}">
                        <a href="{{ route('index.manager') }}">

                            <span class="fa fa-home" style="margin-right: 15px;"></span>
                            Home
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('stock.*') ? 'active' : '' }}">
                        <a href="#stockSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <span class="fa fa-boxes mr-3"></span>Stock
                        </a>
                        <ul class="collapse list-unstyled" id="stockSubmenu">
                            <li class="{{ request()->routeIs('stock.actuel') ? 'active' : '' }}">
                                <a href="{{ route('stock.actuel') }}">Stock
                                    actuels</a>
                            </li>
                            <li class="{{ request()->routeIs('stock.critique') ? 'active' : '' }}">
                                <a href=" {{ route('stock.critique') }}">Stock critique</a>
                            </li>
                        </ul>
                    </li>

                    <li class="{{ request()->routeIs('insert_article.manager', 'articles.list') ? 'active' : '' }}">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <span class="fa fa-briefcase mr-3"></span>Articles
                        </a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li class="{{ request()->routeIs('insert_article.manager') ? 'active' : '' }}">
                                <a href="{{route('insert_article.manager')}}">Nouveau Article</a>
                            </li>
                            <li class="{{ request()->routeIs('articles.list') ? 'active' : '' }}">
                                <a href="{{route('articles.list')}}">Liste des
                                    Articles</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="{{ request()->routeIs('insert_fournisseur.manager', 'fournisseurs.list') ? 'active' : '' }}">
                        <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <span class="fa fa-pencil-ruler" style="margin-right: 15px;"></span>Fournisseurs
                        </a>
                        <ul class="collapse list-unstyled" id="pageSubmenu">
                            <li class="{{ request()->routeIs('insert_fournisseur.manager') ? 'active' : '' }}">
                                <a href="{{ route('insert_fournisseur.manager') }}">
                                    Nouveau Fournisseurs
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('fournisseurs.list') ? 'active' : '' }}">
                                <a href="{{ route('fournisseurs.list') }}">
                                    Liste des Fournisseurs
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="{{ request()->routeIs('demande.aprovisonnement', 'demandeAchat.gc', 'list.demandeachat') ? 'active' : '' }}">
                        <a href="#demandeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <span class="fa fa-file-alt mr-3"></span>Demande
                        </a>
                        <ul class="collapse list-unstyled" id="demandeSubmenu">
                            <li class="{{ request()->routeIs('demande.aprovisonnement') ? 'active' : '' }}">
                                <a href="{{ route('demande.aprovisonnement')}}">Approvisionnement</a>
                            </li>

                            <li class="{{ request()->routeIs('demandeAchat.gc') ? 'active' : '' }}">
                                <a href=" {{ route('demandeAchat.gc')}}">Demander un Achat</a>
                            </li>
                            <li class="{{ request()->routeIs('list.demandeachat') ? 'active' : '' }}">
                                <a href="  {{ route('list.demandeachat')}}">Listes
                                    demande Achat</a>
                            </li>

                        </ul>
                    </li>
                    <li
                        class="{{ request()->routeIs('list.besoinsDepartement', 'demande.nouveauarticle') ? 'active' : '' }}">
                        <a href="#beoinsdepartementSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="fa fa-clipboard-list mr-3"></span>Besoins departement
                        </a>
                        <ul class="collapse list-unstyled" id="beoinsdepartementSubmenu">
                            <li class="{{ request()->routeIs('list.besoinsDepartement') ? 'active' : '' }}">
                                <a href=" {{ route('list.besoinsDepartement')}}">Demande besoins</a>
                            </li>
                            <li class="{{ request()->routeIs('demande.nouveauarticle') ? 'active' : '' }}">
                                <a href="{{ route('demande.nouveauarticle') }}">Nouveau article</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('entree.article', 'sortie.article') ? 'active' : '' }}">
                        <a href="#mouvementSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="fa fa-exchange-alt mr-3"></span>Mouvement
                        </a>
                        <ul class="collapse list-unstyled" id="mouvementSubmenu">
                            <li class="{{ request()->routeIs('entree.article') ? 'active' : '' }}">
                                <a href="{{ route('entree.article') }}">Entrée Articles</a>
                            </li>
                            <li class="{{ request()->routeIs('sortie.article') ? 'active' : '' }}">
                                <a href=" {{ route('sortie.article') }}">Sortie Articles</a>
                            </li>
                        </ul>
                    </li>

                    <!-- New Historique List -->
                    <li class="{{ request()->routeIs('mouvement.entree', 'historiques.sorties') ? 'active' : '' }}">
                        <a href="#historiqueSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="fa fa-history mr-3"></span>Historique
                        </a>
                        <ul class="collapse list-unstyled" id="historiqueSubmenu">
                            <li class="{{ request()->routeIs('mouvement.entree') ? 'active' : '' }}">
                                <a href=" {{ route('mouvement.entree')}}">Mouvement Entrée</a>
                            </li>
                            <li class="{{ request()->routeIs('historiques.sorties') ? 'active' : '' }}">
                                <a href="{{ route('historiques.sorties') }}">Mouvement Sortie</a>
                            </li>
                        </ul>
                    </li>


                    <li class="{{ request()->routeIs('prevision', 'consommation.departement') ? 'active' : '' }}">
                        <a href="#consommationSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="fa fa-chart-line mr-3"></span>Gestion de la Consommation
                        </a>
                        <ul class="collapse list-unstyled" id="consommationSubmenu">
                            <li class="{{ request()->routeIs('prevision') ? 'active' : '' }}">
                                <a href=" {{ route('prevision')}}">
                                    Consommation Article et Prévision
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('consommation.departement') ? 'active' : '' }}">
                                <a href="{{ route('consommation.departement') }}">
                                    Suivi de la Consommation
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="fa fa-sign-out mr-3" style="margin-right: 15px;"></span> Se deconnecter
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout.manager') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>

                <div class="footer">
                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;
                        <script>document.write(new Date().getFullYear());</script>
                    <i class="icon-heart" aria-hidden="true"></i><a href="https://colorlib.com"
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

    <footer>
        <!-- Votre pied de page ici -->
    </footer>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.min.js') }}"></script>
    @stack('scripts')

</body>

</html>