@extends('BackOffice.layouts.app')

@section('title', 'Demande Devis')

@section('head')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="container mt-5">

<div class="title-container">

<h1 class="display-4">Demande devis</h1>
<hr>
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



<!-- Formulaire de recherche -->
<div class="search-container mb-4">
    <form method="GET" action="{{ route('list.devis') }}">
        <div class="row">
            <div class="col-md-3">
                <select class="form-control" name="poste">
                    <option value="">-- Choisir la poste --</option>
                    @foreach($postes as $poste)
                        <option value="{{ $poste->id_poste }}" {{ (request('poste') == $poste->id_poste) ? 'selected' : '' }}>
                            {{ $poste->nom_poste }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" name="status">
                    <option value="">--Status --</option>
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>En attente</option>
                    <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Validee</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="nom_article" class="form-control" placeholder="Rechercher un article"
                    value="{{ request('nom_article') }}">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                <a href="{{ route('list.devis') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>
            </div>

        </div>
    </form>
</div>


<!-- Affichage des demandes d'approvisionnement sous forme de cartes -->
@if($demandedevis->isEmpty())
    <div class="alert alert-warning text-center">Aucune demande trouvée.</div>
@else
    @php
        $exportedReferences = []; // Tableau pour stocker les références déjà affichées
    @endphp

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session(key: 'error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="accordion" id="accordionExample">
        @foreach($demandedevis as $index => $data)

            @php
                // Vérifier si cette référence a déjà affiché le bouton "Livrée(s)"
                $referenceAlreadyDisplayed = in_array($data->reference, $exportedReferences);
            @endphp
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" id="heading{{ $index }}">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $index }}"
                            aria-expanded="true" aria-controls="collapse{{ $index }}">
                            {{ $data->nom_article }} ({{ $data->reference }})
                        </button>
                    </h2>
                    <div class="d-flex align-items-center">

                        @if(!in_array($data->reference, $exportedReferences))

                                <a href="{{ route('generate.devis.pdf', ['id_demande' => $data->id_demande]) }}"
                                    class="btn btn-secondary btn-sm mr-2">
                                    <i class="fas fa-file-export"></i> Exporter
                                </a>
                                @php
                                    $exportedReferences[] = $data->reference; // Ajouter la référence au tableau
                                @endphp
                        @endif

                        <form action="{{ route('delete.devis', $data->id_demande) }}" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');" class="mr-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm icon-btn">
                                <i class="fas fa-trash-alt"></i> <!-- Icône de la corbeille -->
                            </button>
                        </form>

                        <button type="button" class="btn btn-success btn-sm icon-btn btn-update"
                            onclick="openEditModal({{ $data->id_demande }}); event.stopPropagation();">
                            <i class="fa fa-edit"></i> <!-- Icône de l'édition -->
                        </button>
                    </div>


                </div>

                <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <p><strong>Description:</strong> {{ $data->description }}</p>
                        <p><strong>Quantité:</strong> {{ $data->quantite }}</p>
                        <p><strong>Unité:</strong> {{ $data->unite }}</p>
                        <p><strong>Fournisseur:</strong> {{ $data->nom_fournisseur }}</p>
                        <p><strong>Date de demande:</strong> {{ $data->date_demande }}</p>
                        <p><strong>Contact:</strong> {{ $data->contact }}</p>
                        <p><strong>Email:</strong> {{ $data->email_fournisseur }}</p>
                        <p><strong>Date limite:</strong> {{ \Carbon\Carbon::parse($data->date_limite)->format('d-m-Y') }}
                        </p>
                        <p><strong>Statut:</strong>
                            <span
                                class="badge badge-{{ $data->nom_status === 'Validee' ? 'success' : ($data->nom_status === 'En attente' ? 'danger' : 'warning') }}">
                                {{ $data->nom_status }}
                            </span>
                        </p>

                        @if(!$referenceAlreadyDisplayed)
                                <form action="{{ route('approuver.devis', $data->id_demande) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary">Approuver</button>
                                </form>
                                @php
                                    $exportedReferences[] = $data->reference; // Ajouter la référence au tableau pour le bouton Livrée
                                @endphp
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modale pour modifier la demande de devis -->
            <div class="modal fade" id="editDemandeModal_{{ $data->id_demande }}" tabindex="-1" role="dialog"
                aria-labelledby="editDemandeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDemandeModalLabel">Modifier la Demande de Devis</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('update.devis', $data->id_demande) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <!-- Nom de l'article -->
                                    <div class="form-group col-md-6">
                                        <label for="nom_article">Nom de l'article</label>
                                        <input type="text" class="form-control" id="nom_article" name="nom_article"
                                            value="{{ $data->nom_article }}" required>
                                    </div>

                                    <!-- Quantité -->
                                    <div class="form-group col-md-6">
                                        <label for="quantite">Quantité</label>
                                        <input type="number" class="form-control" id="quantite" name="quantite"
                                            value="{{ $data->quantite }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- Description -->
                                    <div class="form-group col-md-12">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            required>{{ $data->description }}</textarea>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- Unité -->
                                    <div class="form-group col-md-6">
                                        <label for="unite">Unité</label>
                                        <input type="text" class="form-control" id="unite" name="unite"
                                            value="{{ $data->unite }}" required>
                                    </div>

                                    <!-- Date Limite -->
                                    <div class="form-group col-md-6">
                                        <label for="date_limite">Date Limite</label>
                                        <input type="date" class="form-control" id="date_limite" name="date_limite"
                                            value="{{ $data->date_limite }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- Nom du Fournisseur -->
                                    <div class="form-group col-md-6">
                                        <label for="nom_fournisseur">Nom du Fournisseur</label>
                                        <input type="text" class="form-control" id="nom_fournisseur" name="nom_fournisseur"
                                            value="{{ $data->nom_fournisseur }}" required>
                                    </div>

                                    <!-- Lieu du Fournisseur -->
                                    <div class="form-group col-md-6">
                                        <label for="lieu_fournisseur">Lieu du Fournisseur</label>
                                        <input type="text" class="form-control" id="lieu_fournisseur" name="lieu_fournisseur"
                                            value="{{ $data->lieu_fournisseur }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- Email du Fournisseur -->
                                    <div class="form-group col-md-6">
                                        <label for="email_fournisseur">Email du Fournisseur</label>
                                        <input type="email" class="form-control" id="email_fournisseur" name="email_fournisseur"
                                            value="{{ $data->email_fournisseur }}" required>
                                    </div>

                                    <!-- Contact du Fournisseur -->
                                    <div class="form-group col-md-6">
                                        <label for="contact">Contact du Fournisseur</label>
                                        <input type="text" class="form-control" id="contact" name="contact"
                                            value="{{ $data->contact }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- Nom de l'Acheteur -->
                                    <div class="form-group col-md-6">
                                        <label for="nom_acheteur">Nom de l'Acheteur</label>
                                        <input type="text" class="form-control" id="nom_acheteur" name="nom_acheteur"
                                            value="{{ $data->nom_acheteur }}" required>
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label for="contact_acheteur">Contact de l'Acheteur</label>
                                        <input type="text" class="form-control" id="contact_acheteur" name="contact_acheteur"
                                            value="{{ $data->contact_acheteur }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- Contact de l'Acheteur -->
                                    <!-- Email de l'Acheteur -->
                                    <div class="form-group col-md-12">
                                        <label for="email_acheteur">Email de l'Acheteur</label>
                                        <input type="email" class="form-control" id="email_acheteur" name="email_acheteur"
                                            value="{{ $data->email_acheteur }}" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        @endforeach
    </div>
@endif

</div>
@push('scripts')

    <script>


        function openEditModal(demandeId) {
            $('#editDemandeModal_' + demandeId).modal('show');
        }
    </script>
@endpush
@endsection