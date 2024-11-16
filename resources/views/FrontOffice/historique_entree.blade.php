@extends('FrontOffice.layouts.app')

@section('title', 'Historique-Entree')

@section('head')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="container mt-5">
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="title-container">
        <h1 class="display-4">Historique des Mouvements d'Entrée</h1>
        <hr>
       
    </div>

<!-- Formulaire de recherche entre deux dates -->
<div class="search-container mb-4">


<strong class="text-muted">Rechercher les mouvements d'entrée entre deux dates :</strong>
    <form method="GET" action="{{ route('mouvement.entree') }}">
        <div class="row">
            <div class="col-md-4">
              
                <input type="date" name="date_debut" class="form-control" 
                       value="{{ request('date_debut') }}" id="date_debut">
            </div>
            <div class="col-md-4">
               
                <input type="date" name="date_fin" class="form-control" 
                       value="{{ request('date_fin') }}" id="date_fin">
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                <a href="{{ route('mouvement.entree') }}" class="btn btn-reset"><i class="fas fa-redo"></i></a>
            </div>
        </div>
    </form>
</div>




    <!-- Tableau des résultats -->
    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">ID Article</th>
                <th scope="col">Nom Article</th>
                <th scope="col">Quantité</th>
                <th scope="col">Unité</th>
                <th scope="col">Prix(Unitaire en Ariary)</th>
                <th scope="col">Fournisseurs</th>
                <th scope="col">Date d'Entrée</th>
                <!-- Ajoute d'autres colonnes selon les données dans ta vue -->
            </tr>
        </thead>
        <tbody>
            @if($historique_entrees->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">Aucun Mouvement trouvé.</td>
                </tr>
            @else
                @foreach($historique_entrees as $historique_entree)
                    <tr>
                        <td>{{ $historique_entree->reference_article }}</td>
                        <td>{{ $historique_entree->nom_article }}</td>
                        <td>{{ $historique_entree->quantite }}</td>
                        <td>{{ $historique_entree->unite }}(s)</td>
                        <td>{{ number_format($historique_entree->prix, 2) }}</td>
                        <td>{{ $historique_entree->fournisseur }}</td>
                        <td>{{ \Carbon\Carbon::parse($historique_entree->date_entree)->format('d-m-Y') }}</td>
            
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
    {{ $historique_entrees->links() }} 
    </div>
</div>
@endsection