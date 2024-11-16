@extends('BackOffice.layouts.app')

@section('title', 'Demande Achat')

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
        <h1 class="ml-3 mb-0 display-4">Demandes d'achats par poste</h1>

        <form method="GET" action="{{ route('demande.Achat.admin') }}" class="d-flex">
            <!-- Category Select Dropdown -->
            <div class="col-md-8">
                <select class="form-control" name="poste">
                    <option value="">-- Sélectionnez un poste --</option>
                    @foreach($postes as $poste)
                        <option value="{{ $poste->id_poste }}" {{ (request('poste') == $poste->id_poste) ? 'selected' : '' }}>
                            {{ $poste->nom_poste }}
                        </option>
                    @endforeach
                </select>
            </div>

            

            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
            </div>
        </form>
    </div>


    @if($demandeAchats->isEmpty())
        <div class="alert alert-warning text-center">Aucune demande trouvée.</div>
    @else

        <div class="row">
            @foreach($demandeAchats as $demandeAchat)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header text-center bg-dark text-white">
                            <h5 class="card-title">{{ $demandeAchat->nom_article }} </h5>
                            <small class="text-muted">{{ $demandeAchat->nom_departement}}</small>

                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <strong>Description d'article :</strong> {{ $demandeAchat->description }}<br>
                                <strong>Motifs :</strong> {{ $demandeAchat->motif_demande}}<br>

                            </p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Quantité demandée
                                    <span class="badge bg-info"> {{ $demandeAchat->quantite_demande }}
                                        {{ $demandeAchat->unite }}(s)</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Date de demande
                                    <span
                                        class="badge btn-valider">{{ \Carbon\Carbon::parse($demandeAchat->date_envoi)->format('d-m-Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Statut
                                    <span class="badge {{ $demandeAchat->id_status === 1 ? 'badge-danger' : 'badge-warning' }}">
                                        {{ $demandeAchat->id_status === 1 ? 'En attente' : 'Validee' }}
                                    </span>
                                </li>
                            </ul>
      
                            @if($demandeAchat->id_status === 1)
                                <form action="{{ route('valider.demandeAchat', $demandeAchat->id_demande) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success w-100 mt-3">Approuver la demande</button>
                                </form>

                            @endif


                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $demandeAchats->links() }}
        </div>
    @endif
</div>



@endsection