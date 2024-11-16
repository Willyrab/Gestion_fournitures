@extends('BackOffice.layouts.app')

@section('title', 'Demande articles')

@section('head')
<link rel="stylesheet" href="{{ asset('css/new.css') }}">
<link rel="stylesheet" href="{{ asset('css/loader.css') }}">

@endsection

@section('content')
<div class="container mt-5">
    <!-- Affichage des messages de succès -->

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Demandes nouveau articles</h1>

        <form id="chiffresDaffairesForm" method="GET" action="{{ route('demande.nouveau_artcile.admin')}}"
            class="d-flex">
            <div class="col-md-4">
                <input type="date" name="date_envoi" class="form-control" placeholder="Rechercher par date"
                    value="{{ request('date_envoi') }}">
            </div>

            <div class="col-md-4">

                <select class="form-control" name="id_poste">
                    <option value="">--poste--</option>
                    @foreach($postes as $poste)
                        <option value="{{ $poste->id_poste }}" {{ request('id_poste') == $poste->id_poste ? 'selected' : '' }}>
                            {{ $poste->nom_poste }} Ref {{ $poste->lieu_poste }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center">
                <button type="submit" class="btn btn-primary">Rechercher</button>
                <a href="{{ route('demande.nouveau_artcile.admin') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>
            </div>
        </form>
    </div>


    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Article</th>
                <th scope="col">Description</th>
                <th scope="col">Quantité</th>
                <th scope="col">Unité</th>
                <th scope="col">Motifs</th>
                <th scope="col">Date trasfert</th>
                <th scope="col">Departement</th>
                <th scope="col">Action</th>


            </tr>
        </thead>
        <tbody id="resultsBody">
            @if($demandes->isEmpty())
                <tr>
                    <td colspan="12" class="text-center">Aucune réponse trouvée.</td>
                </tr>
            @else
                @foreach($demandes as $demande)
                    <tr>
                        <td>{{ $demande->nom_article }}</td>
                        <td>{{ $demande->description }}</td>
                        <td>{{ $demande->quantite }}</td>
                        <td>{{ $demande->unite }}</td>
                        <td>{{ $demande->motif_demande }}</td>
                        <td>{{ \Carbon\Carbon::parse($demande->date_envoi)->format('d-m-Y') }}</td>
                        <td>{{ $demande->nom_departement }}{{ $demande->id_status }}</td>
                        <td>
                            <!-- Bouton Valider -->
                            <!-- Bouton Valider -->
                            @if($demande->id_status === 4) <!-- Statut "Transféré" -->
                                <form action="{{ route('approuver.NDarticle', $demande->id_nouveaubesoins) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Validé</button>
                            @endif

                        </td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>


    <div class="d-flex justify-content-center">
        {{ $demandes->links() }} <!-- Pagination -->
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#chiffresDaffairesForm').submit(function (event) {
                event.preventDefault(); // Empêcher le formulaire de soumettre normalement

                var loaderHTML = `
                                    <tr>
                                        <td colspan="12">
                                            <div class="loader-wrapper">
                                                <div class="rubik-loader"></div>
                                            </div>
                                        </td>
                                    </tr>
                                `;

                $('#resultsBody').html(loaderHTML); // Afficher le loader dans le tableau

                // Soumettre le formulaire après 2 secondes
                setTimeout(function () {
                    $('#chiffresDaffairesForm')[0].submit(); // Soumettre le formulaire
                }, 2000); // 2000 millisecondes = 2 secondes
            });
        });


    </script>
@endpush

@endsection