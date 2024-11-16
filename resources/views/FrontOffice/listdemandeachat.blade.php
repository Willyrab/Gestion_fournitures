@extends('FrontOffice.layouts.app')
@section('title', 'Demande Achat')
@section('head')
<link rel="stylesheet" href="{{ asset('css/new.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="title-container">

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

    </div>
    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Liste des demandes d'achats</h1>

        <form method="GET" action=" {{ route('list.demandeachat') }}" class="d-flex">
            <div class="col-md-4">

                <input type="date" name="date_envoi" class="form-control" placeholder="Unité"
                    value="{{ request('date_envoi') }}">
            </div>

            <div class="col-md-4">
                <input type="text" name="nom_article" class="form-control" placeholder="article"
                    value="{{ request('nom_article') }}">

            </div>

            <div class="col-md-2 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr">Rechercher</button>
                <a href="{{ route('list.demandeachat') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>

            </div>


        </form>
    </div>
    <!-- Tableau des résultats -->
    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Départements</th>
                <th scope="col">Article</th>
                <th scope="col">Description</th>
                <th scope="col">Quantité</th>
                <th scope="col">Unité</th>
                <th scope="col">Date d'envoie</th>
                <th scope="col">Statut</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if($demandeachats->isEmpty())
                <tr>
                    <td colspan="8" class="text-center">Aucun demande trouvée.</td>
                </tr>
            @else
                @foreach($demandeachats as $demandeachat)
                    <tr>
                        <td>{{ $demandeachat->nom_departement }}</td>
                        <td>{{ $demandeachat->nom_article }}</td>
                        <td>{{ $demandeachat->description }}</td>
                        <td>{{ $demandeachat->quantite_demande }}</td>
                        <td>{{ $demandeachat->unite}}(s)</td>
                        <td>{{ \Carbon\Carbon::parse($demandeachat->date_envoi)->format('d-m-Y') }}</td>

                        <td>
                            @if($demandeachat->nom_status === 'En attente')
                                <span class="badge badge-danger">{{ $demandeachat->nom_status }}</span>
                            @elseif($demandeachat->nom_status === 'Validee')
                                <span class="badge badge-success">{{ $demandeachat->nom_status }}</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-success edit-article-btn"
                                onclick="openEditModal({{ $demandeachat->id_demande }}); event.stopPropagation();">
                                <i class="fa fa-edit"></i>&nbsp;Modifier
                            </button>
                        </td>
                    </tr>

                    <!-- Modale pour modifier un article -->
                    <div class="modal fade" id="editArticleModal_{{ $demandeachat->id_demande }}" tabindex="-1" role="dialog"
                        aria-labelledby="editArticleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editArticleModalLabel">Modifier l'article</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('modifier.DA', $demandeachat->id_demande) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="unite">Quantité</label>
                                            <input type="number" class="form-control" id="unite" name="quantite"
                                                value="{{ $demandeachat->quantite_demande }}" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Enregistrer la
                                            modification</button>
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
        {{ $demandeachats->links() }}
    </div>

</div>
@push('scripts')
    <script>
        function openEditModal(articleId) {
            $('#editArticleModal_' + articleId).modal('show');
        }
    </script>
@endpush

@endsection