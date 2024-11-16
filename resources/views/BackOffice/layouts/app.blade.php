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
          style="background-image: url({{ asset('images/IMG_3499.png') }});"></a>
        <ul class="list-unstyled components mb-5">

          <li class="{{ request()->routeIs('index.admin') ? 'active' : '' }}">
            <a href="{{ route('index.admin') }}">

              <span class="fa fa-home" style="margin-right: 15px;"></span>
              Home
            </a>
          </li>

          <li class="{{ request()->routeIs('*.departement') ? 'active' : '' }}">
            <a href="#departementSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
              <span class="fa fa-building mr-3"></span>Département
            </a>
            <ul class="collapse list-unstyled" id="departementSubmenu">
              <li class="{{ request()->routeIs('creation.departement') ? 'active' : '' }}">
                <a href="{{ route('creation.departement') }}">Insérer un département</a>
              </li>
              <li class="{{ request()->routeIs('liste.departement') ? 'active' : '' }}">
                <a href="{{ route('liste.departement')}}">Liste des départements</a>
              </li>
            </ul>
          </li>

          <li class="{{ request()->routeIs('liste.poste') ? 'active' : '' }}">
            <a href="{{ route('liste.poste') }}">

              <span class="fa fa-users" style="margin-right: 15px;"></span>
              Poste
            </a>
          </li>

          <li class="{{ request()->routeIs('gestionnaire.centre') ? 'active' : '' }}">
            <a href="{{ route('gestionnaire.centre') }}">

              <span class="fa fa-users" style="margin-right: 15px;"></span>
              Gestionnaire_centre
            </a>
          </li>

          <li class="{{ request()->routeIs('demande.*') ? 'active' : '' }}">
            <a href="#demandeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
              <span class="fa fa-file-alt mr-3"></span>Demande
            </a>
            <ul class="collapse list-unstyled" id="demandeSubmenu">
              <li class="{{ request()->routeIs('demande.approvisionnement.admin') ? 'active' : '' }}">
                <a href="{{ route('demande.approvisionnement.admin')}}">Demande Approvisionnement</a>
              </li>
              <li class="{{ request()->routeIs('demande.Achat.admin') ? 'active' : '' }}">
                <a href="{{ route('demande.Achat.admin')}}">Demande Achat</a>
              </li>

            </ul>
          </li>

          <!-- Nouveau Menu Achat -->
          <li class="{{ request()->routeIs('achat.fournisseur', 'achat.list.bc') ? 'active' : '' }}">
            <a href="#achatSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
              <span class="fa fa-shopping-cart mr-3"></span>Achat
            </a>
            <ul class="collapse list-unstyled" id="achatSubmenu">
              <li class="{{ request()->routeIs('achat.fournisseur') ? 'active' : '' }}">
                <a href="{{ route('achat.fournisseur') }}">Article existant</a>
              </li>
              <li class="{{ request()->routeIs('achat.list.bc') ? 'active' : '' }}">
                <a href="{{ route('achat.list.bc') }}">Bon de commande</a>
              </li>
            </ul>
          </li>



          <li class="{{ request()->routeIs('creer_demande.devis', 'list.devis') ? 'active' : '' }}">
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span
                class="fa fa-file-invoice" style="margin-right: 15px;"></span>Devis</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">

              <li class="{{ request()->routeIs('creer_demande.devis') ? 'active' : '' }}">
                <a href="{{ route('creer_demande.devis')}}">Demande Devis</a>
              </li>
              <li class="{{ request()->routeIs('list.devis') ? 'active' : '' }}">
                <a href="{{ route('list.devis')}}"> liste Devis</a>
              </li>
            </ul>
          </li>

          <li class="{{ request()->routeIs('prevision', 'consommation.departement') ? 'active' : '' }}">
            <a href="#consommationSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
              <span class="fa fa-chart-line mr-3"></span>Gestion de la Consommation
            </a>
            <ul class="collapse list-unstyled" id="consommationSubmenu">
              <li class="{{ request()->routeIs('prevision.admin') ? 'active' : '' }}">
                <a href="{{ route('prevision.admin')}}">
                  Consommation Article et Prévision
                </a>
              </li>
      
            </ul>
          </li>
          <li class="{{ request()->routeIs('fournisseur.articles') ? 'active' : ' ' }}">
            <a href="{{ route('fournisseur.articles')}}"><span class="fa fa-pencil-ruler"
                style="margin-right: 15px;"></span>Fournisseurs d'articles</a>
          </li>
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

          <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle Menu</span>
          </button>
          <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
              <li>
                <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <span class="fa fa-sign-out mr-3" style="margin-right: 15px;"></span> Se deconnecter
                </a>
              </li>
              <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display: none;">
                @csrf
              </form>

            </ul>
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