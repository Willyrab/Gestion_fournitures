@extends('FrontOffice.layouts.app')

@section('title', 'Listes besoins articles')

@section('head')
<link rel="stylesheet" href="{{ asset('css/new.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Affichage des erreurs de validation -->
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
        <h1 class="ml-3 mb-0 display-4">Voir les besoins departements</h1>

        <form method="GET" action=" {{ route('list.besoinsDepartement')}}" class="d-flex">
            <div class="col-md-4">
                <input type="date" name="date_demande" class="form-control" placeholder="Rechercher par date"
                    value="{{ request('date_demande') }}">
            </div>

            <div class="col-md-4">
            <select class="form-control" name="departement">
            <option value="">-- Sélectionnez un département --</option>
            @foreach($departements as $departement)
                <option value="{{ $departement->id_departement }}" 
                    {{ (request('departement') == $departement->id_departement) ? 'selected' : '' }}>
                    {{ $departement->nom_departement }}
                </option>
            @endforeach
        </select>
            </div>

            <div class="col-md-2 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr">Rechercher</button>
                <a href="{{ route('list.besoinsDepartement') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>

            </div>
            
         
        </form>
    </div>

    @if($demandes->isEmpty())
        <div class="alert alert-warning text-center">Aucune demande trouvée.</div>
    @else
        <div class="accordion" id="accordionExample">
            @foreach($demandes as $index => $data)
                <div class="card">
                    <div class="card-header" id="heading{{ $index }}">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                                {{ $data->nom_article }} ({{ $data->reference }})
                            </button>
                        </h2>

                        <!-- Bouton de validation -->
                        @if($data->id_status === 1) <!-- Vérifier si la demande est en attente -->
                            <form action="{{ route('valider.besoins', $data->id_besoin) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT') <!-- Utiliser PUT pour les mises à jour -->
                                <button type="submit" class="btn btn-outline-primary btn-valider">
                                    <i class="fa fa-check"></i>&nbsp; Valider
                                </button>
                            </form>
                        @endif
                    </div>

                    <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p><strong>Departement:</strong> {{ $data->nom_departement }}</p>
                            <p><strong>Quantité:</strong> {{ $data->quantite }} ({{ $data->unite }})</p>
                            <p><strong>Date de demande:</strong>
                                {{ \Carbon\Carbon::parse($data->date_demande)->format('d-m-Y') }}</p>
                            <p><strong>Statut:</strong> 
                            <span
                                    class="badge badge-{{ $data->nom_status === 'Validee' ? 'success' : ($data->nom_status === 'En attente' ? 'danger' : 'warning') }}">
                                    {{ $data->nom_status }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $demandes->links() }} <!-- Pagination -->
        </div>
    @endif
</div>
@endsection