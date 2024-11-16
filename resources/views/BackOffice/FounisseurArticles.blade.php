@extends('BackOffice.layouts.app')

@section('title', 'Fournisseurs articles')

@section('head')
<link rel="stylesheet" href="{{ asset('css/recherche.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('css/stockactuel.css') }}"> -->
@endsection
@section('content')
<div class="container mt-5">



    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Founisseurs d'articles par poste</h1>

        <form method="GET" action="{{ route('fournisseur.articles') }}" class="d-flex">
            <!-- Dropdown de sélection du poste -->
            <div class="col-md-4">
                <select class="form-control" name="poste">
                    <option value="">Sélectionnez un poste</option>
                    @foreach($postes as $poste)
                        <option value="{{ $poste->id_poste }}" {{ request('poste') == $poste->id_poste ? 'selected' : '' }}>
                            {{ $poste->nom_poste }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Champ de recherche pour le nom de l'article -->
            <div class="col-md-4">
                <input type="text" name="article" class="form-control" placeholder="Rechercher un article"
                    value="{{ request('article') }}">
            </div>

            <div class="col-md-2 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
            </div>
        </form>


        <!-- Title/Label Next to Search Bar -->
    </div>

    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Référence</th>
                <th scope="col">Article</th>
                <th scope="col">Description</th>
                <th scope="col">Unité</th>
                <th scope="col">Fournisseurs</th>
                <th scope="col">Lieu</th>
                <th scope="col">email</th>
                <th scope="col">contact</th>
                <th scope="col">Prix(Ariary)</th>
                <th scope="col">Inventaire</th>
                <!-- Ajoute d'autres colonnes selon les données dans ta vue -->
            </tr>
        </thead>
        <tbody>
            @if($FounisseurArticles->isEmpty())
                <tr>
                    <td colspan="10" class="text-center">Aucun reponse trouvé.</td>
                </tr>
            @else
                @foreach($FounisseurArticles as $FounisseurArticle)
                    <tr>
                        <td>{{ $FounisseurArticle->reference_article }}</td>
                        <td>{{ $FounisseurArticle->nom_article }}</td>
                        <td>{{ $FounisseurArticle->description }}</td>
                        <td>{{ $FounisseurArticle->unite }}</td>
                        <td>{{ $FounisseurArticle->nom_fournisseur }}</td>
                        <td>{{ $FounisseurArticle->lieu_fournisseur }}</td>
                        <td>{{ $FounisseurArticle->email_fournisseur }}</td>
                        <td>{{ $FounisseurArticle->contact_fournisseur }}</td>
                        <td>{{ number_format($FounisseurArticle->prix, 2) }}</td>
                        <td>{{ $FounisseurArticle->nom_gestionnaire }}</td>

                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $FounisseurArticles->links() }}
    </div>


</div>
@endsection