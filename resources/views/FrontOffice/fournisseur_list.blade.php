@extends('FrontOffice.layouts.app')
@section('title', 'Liste des Fournisseurs')

@section('head')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">

@endsection

@section('content')
<div class="container mt-5">
    <div class="title-container">
        <h1 class="display-4">Liste des Fournisseurs</h1>
        <hr>
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

    <!-- Conteneur avec ombre -->
    <div class="search-container mb-4">
        <!-- Formulaire de recherche -->
        <form method="GET" action="{{ route('fournisseurs.list') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="nom_fournisseur" class="form-control" placeholder="Nom du fournisseur"
                        value="{{ request('nom_fournisseur') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="lieu_fournisseur" class="form-control" placeholder="Lieu"
                        value="{{ request('lieu_fournisseur') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="contact" class="form-control" placeholder="Contact"
                        value="{{ request('contact') }}">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                    <a href="{{ route('fournisseurs.list') }}" class="btn  btn-reset"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
    </div>


    <!-- Tableau des résultats -->
    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Lieu</th>
                <th scope="col">Adresse électronique</th>
                <th scope="col">Contact</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if($fournisseurs->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">Aucun fournisseur trouvé.</td>
                </tr>
            @else
                @foreach($fournisseurs as $fournisseur)
                    <tr>
                        <td>{{ $fournisseur->nom_fournisseur }}</td>
                        <td>{{ $fournisseur->lieu_fournisseur }}</td>
                        <td>{{ $fournisseur->email }}</td>
                        <td>{{ $fournisseur->contact }}</td>
                        <td>

                            <button type="button" class="btn btn-outline-success edit-article-btn"
                                onclick="openEditModal({{ $fournisseur->id_fournisseur }}); event.stopPropagation();">
                                <i class="fa fa-edit"></i>&nbsp; Modifier
                            </button>

                        </td>
                    </tr>

                    <!-- Modale pour modifier un fournisseur -->
                    <div class="modal fade" id="editFournisseurModal_{{ $fournisseur->id_fournisseur }}" tabindex="-1"
                        role="dialog" aria-labelledby="editFournisseurModalLabel" aria-hidden="true">

                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editArticleModalLabel">Modifier le fournisseur</h5>

                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('update.fournisseur', $fournisseur->id_fournisseur) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" id="fournisseurId" name="fournisseurId">

                                        <div class="form-group">
                                            <label for="nom_fournisseur">Nom</label>
                                            <input type="text" class="form-control" id="nom_fournisseur" name="nom_fournisseur"
                                                value="{{ $fournisseur->nom_fournisseur }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="lieu_fournisseur">Lieu</label>
                                            <input type="text" class="form-control" id="lieu_fournisseur"
                                                name="lieu_fournisseur" value="{{ $fournisseur->lieu_fournisseur }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Adresse Electronique</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $fournisseur->email }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="contact">Contact</label>
                                            <input type="text" class="form-control" id="contact" name="contact"
                                                value="{{ $fournisseur->contact }}" required>
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
        {{ $fournisseurs->links() }}
    </div>

</div>
@push('scripts')
    <script>
        function openEditModal(fournisseurId) {
            $('#editFournisseurModal_' + fournisseurId).modal('show');
        }
    </script>
@endpush
@endsection