@extends('BackOffice.layouts.app')
@section('title', 'Gestionnaires-centre')

@section('head')
<meta name="description"
    content="page pour afficher le chiffre d’affaire et les gains par mois de MADA-IMMO entre 2 dates">
<meta name="keywords" content="Chiffres d'affaires et Gains par mois">
<!-- Si vous utilisez un fichier CSS externe -->
<link rel="stylesheet" href="{{ asset('css/recherche.css')}}">
@endsection

@section('content')
<div class="container mt-5">

    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Liste des gestionnaires de centre</h1>

        <form method="GET" action="{{ route('gestionnaire.centre') }}" class="d-flex">
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
    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Email</th>
                <th scope="col">Poste</th>
                <th scope="col">Lieu</th>
            </tr>
        </thead>
        <tbody id="resultsBody">
            @if($gestionnaires->isEmpty())
                <tr>
                    <td colspan="4" id="noResults">Aucune location trouvée pour les critères donnés.</td>
                </tr>
            @else
                @foreach($gestionnaires as $gestionnaire)
                    <tr>
                        <td>{{ $gestionnaire->nom_gestionnaire }}</td>
                        <td>{{ $gestionnaire->email }}</td>
                        <td>{{ $gestionnaire->poste->nom_poste }}</td>
                        <td>{{ $gestionnaire->poste->lieu_poste }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection