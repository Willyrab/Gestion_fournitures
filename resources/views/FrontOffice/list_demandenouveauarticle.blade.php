@extends('FrontOffice.layouts.app')

@section('title', 'Demande nouveau article')

@section('head')
<link rel="stylesheet" href="{{ asset('css/recherche.css') }}">
<link rel="stylesheet" href="{{ asset('css/card.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Demande des nouveaux articles</h1>

        <form method="GET" action="{{ route('demande.nouveauarticle') }}" class="d-flex">
            <!-- Category Select Dropdown -->
            <div class="col-md-7">
                <input type="date" name="date_demande" class="form-control" placeholder="Rechercher par date"
                    value="{{ request('date_demande') }}">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-primary ">Rechercher</button>
            </div>
        </form>
    </div>

    <!-- Affichage des demandes d'approvisionnement sous forme de cartes -->
    @if($demandes->isEmpty())
        <div class="alert alert-warning text-center">Aucune demande trouvée.</div>
    @else
        <div class="row">
            @foreach($demandes as $demande)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">

                        <div class="card-header text-center bg-dark text-white">

                            <h5 class="card-title mb-0">{{ $demande->nom_article }}</h5>
                            <small class="text-muted">{{ $demande->nom_departement }}</small>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <strong>Description d'article :</strong> {{ $demande->description }}<br>
                                <strong>Motifs :</strong> {{ $demande->motif_demande }}<br>

                            </p>

                            <!-- Badges pour les sections -->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Quantité demandée
                                    <span class="badge bg-info">{{ $demande->quantite }} {{ $demande->unite }}(s)</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Date de demande
                                    <span
                                        class="badge btn-valider">{{ \Carbon\Carbon::parse($demande->date_demande)->format('d-m-Y') }}</span>
                                </li>
                             
                            </ul>

    
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="d-flex justify-content-center">
            {{ $demandes->links() }}
        </div>
    @endif
</div>
@endsection