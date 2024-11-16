@extends('FrontOffice.Departement.layouts.departement')

@section('title', 'Listes demande articles')

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
        <h1 class="ml-3 mb-0 display-4">Nouveau demandes effectuées</h1>

        <form id="chiffresDaffairesForm" method="GET" action="{{ route('liste_NDarticle.departement')}}" class="d-flex">
            <div class="col-md-4">
                <input type="date" name="date_demande" class="form-control" placeholder="Rechercher par date"
                    value="{{ request('date_demande') }}">
            </div>

            <div class="col-md-4">
                <input type="text" name="nom_article" class="form-control" placeholder="Rechercher un article"
                    value="{{ request('nom_article') }}">
                
            </div>


            <div class="col-md-2 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                <a href="{{ route('liste_NDarticle.departement') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>

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
                <th scope="col">Date demande</th>
                <th scope="col">Statut</th>
                <th scope="col">Actions</th>

            </tr>
        </thead>
        <tbody id="resultsBody">
            @if($demandes->isEmpty())
                <tr>
                    <td colspan="8" class="text-center">Aucune réponse trouvée.</td>
                </tr>
            @else
                @foreach($demandes as $demande)
                    <tr>
                        <td>{{ $demande->nom_article }}</td>
                        <td>{{ $demande->description }}</td>
                        <td>{{ $demande->quantite }}</td>
                        <td>{{ $demande->unite }}</td>
                        <td>{{ $demande->motif_demande }}</td>
                        <td>{{ \Carbon\Carbon::parse($demande->date_demande)->format('d-m-Y') }}</td>
                        <td>
                            @if($demande->status->nom_status === 'En attente')
                                <span class="badge badge-danger">{{ $demande->status->nom_status }}</span>
                            @elseif($demande->status->nom_status === 'Validee')
                                <span class="badge badge-success">{{ $demande->status->nom_status }}</span>
                            @else
                                <span class="badge badge-warning">{{ $demande->status->nom_status }}</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-success edit-article-btn"
                                onclick="openEditModal({{ $demande->id_nouveaubesoins }}); event.stopPropagation();">
                                <i class="fa fa-magic"></i>&nbsp; Modifier
                            </button>
                        </td>

                    </tr>
                    <!-- Modale pour modifier un article -->
                    <div class="modal fade" id="editDemandeModal_{{ $demande->id_nouveaubesoins }}" tabindex="-1" role="dialog"
                        aria-labelledby="editArticleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editArticleModalLabel">Modifier l'article</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('update.demande', $demande->id_nouveaubesoins) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" id="articleId" name="articleId"
                                            value="{{ $demande->id_nouveaubesoins }}">

                                        <div class="form-group">
                                            <label for="nom">Article</label>
                                            <input type="text" class="form-control" id="nom_article" name="nom_article"
                                                value="{{ $demande->nom_article }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" placeholder="Décrivez l'article" name="description"
                                                rows="3" required>{{ $demande->description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="quantite">Quantité demandée</label>
                                            <input type="number" class="form-control" id="quantite" name="quantite"
                                                value="{{ $demande->quantite }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="unite">Unité</label>
                                            <input type="text" class="form-control" id="unite" name="unite"
                                                value="{{ $demande->unite }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="motifs">Motifs</label>
                                            <textarea class="form-control" placeholder="Décrivez le motif de la demande"
                                                name="motifs" rows="3" required>{{ $demande->motif_demande }}</textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Enregistrer les
                                            modifications</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <td colspan="8">
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

        function openEditModal(demandeId) {
            $('#editDemandeModal_' + demandeId).modal('show');
        }
    </script>
@endpush

@endsection