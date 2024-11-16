@extends('BackOffice.layouts.app')

@section('title', 'Bon de Commande')

@section('head')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="container mt-5">

    <div class="title-container">

        <h1 class="display-4">Bon de Commande</h1>
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
            <form method="GET" action="{{ route('achat.list.bc') }}">
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
                            <option value="5" {{ request('status') == 5 ? 'selected' : '' }}>Livrée</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_achat" class="form-control" placeholder="Rechercher un article"
                            value="{{ request('date_achat') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                        <a href="{{ route('achat.list.bc') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>
                    </div>

                </div>
            </form>
        </div>

    <!-- Affichage des demandes d'approvisionnement sous forme de cartes -->
    @if($achats->isEmpty())
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
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="accordion" id="accordionExample">
            @foreach($achats as $index => $data)

                @php
                    // Vérifier si cette référence a déjà affiché le bouton "Livrée(s)"
                    $referenceAlreadyDisplayed = in_array($data->reference_achat, $exportedReferences);
                @endphp
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" id="heading{{ $index }}">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                                {{ $data->nom_article }} ({{ $data->reference_achat }})
                            </button>
                        </h2>

                        <div class="d-flex align-items-center">

                            @if(!in_array($data->reference_achat, $exportedReferences))
                                        <a href="{{ route('generate.boncommande.pdf', $data->id_achat) }}"
                                            class="btn btn-secondary btn-sm mr-2">
                                            <i class="fas fa-file-export"></i> Exporter
                                        </a>
                                        @php
                                            $exportedReferences[] = $data->reference_achat; // Ajouter la référence au tableau
                                        @endphp
                            @endif

                            <!-- Bouton Supprimer avec icône -->
                            <form action="{{ route('master', $data->id_achat) }}" method="POST"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');" class="mr-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm icon-btn">
                                    <i class="fas fa-trash-alt"></i> <!-- Icône de la corbeille -->
                                </button>
                            </form>

                            <!-- Bouton Modifier avec icône -->
                            <button type="button" class="btn btn-success btn-sm icon-btn btn-update"
                                onclick="openEditModal({{ $data->id_achat }}); event.stopPropagation();">
                                <i class="fa fa-edit"></i> <!-- Icône de l'édition -->
                            </button>
                        </div>

                    </div>

                    <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p><strong>Description:</strong> {{ $data->description }}</p>
                            <p><strong>Quantité:</strong> {{ $data->quantite }} {{ $data->unite }}(s)</p>
                            <p><strong>Unité:</strong> {{ $data->unite }}</p>
                            <p><strong>Prix Unitaire:</strong> {{ $data->prix }} Ar</p>
                            <p><strong>Poste:</strong> {{ $data->poste_achat }} ({{ $data->lieu_poste_achat }})</p>
                            <p><strong>Fournisseur:</strong> {{ $data->nom_fournisseur }} ({{ $data->email_fournisseur }})</p>
                            <p><strong>Contact:</strong> {{ $data->contact_fournisseur }}</p>
                            <p><strong>Date de
                                    Livraison:</strong>{{ \Carbon\Carbon::parse($data->date_livraison)->format('d-m-Y') }}</p>
                            <p><strong>Lieu de Livraison:</strong> {{ $data->lieu_livraison }}</p>
                            <p><strong>Condition de paiement:</strong> {{ $data->condition_paiement }}</p>
                            <p><strong>Suivis par:</strong> {{ $data->nom_acheteur }}</p>
                            <p><strong>Statut:</strong>
                                <span
                                    class="badge badge-{{ $data->nom_status === 'Livree' ? 'success' : ($data->nom_status === 'En attente' ? 'danger' : 'warning') }}">
                                    {{ $data->nom_status }}
                                </span>
                            </p>
                            <!-- Afficher le bouton Livrée(s) seulement si la référence n'a pas encore été affichée -->
                            @if(!$referenceAlreadyDisplayed)
                                        <form action="{{ route('valider.bonde.commande', $data->id_achat) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary">Livrée(s)</button>
                                        </form>
                                        @php
                                            $exportedReferences[] = $data->reference_achat;
                                        @endphp
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Modal Modifier -->
                <div class="modal fade" id="editDemandeModal_{{ $data->id_achat }}" tabindex="-1" role="dialog"
                    aria-labelledby="editArticleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editArticleModalLabel">Modifier la demande</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('update.BC', $data->id_achat) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="quantite">Quantité</label>
                                        <input type="number" class="form-control" id="quantite" name="quantite"
                                            value="{{ $data->quantite }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="prix">Prix</label>
                                        <input type="number" class="form-control" id="unite" name="prix"
                                            value="{{ $data->prix }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Date de livraison</label>
                                        <input type="date" class="form-control" id="unite" name="date_livraison"
                                            value="{{ $data->date_livraison }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Lieu de livraison</label>
                                        <input type="text" class="form-control" id="unite" name="lieu_livraison"
                                            value="{{ $data->lieu_livraison }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="prix">Condition de paiement</label>
                                        <input type="text" class="form-control" id="unite" name="condition_paiement"
                                            value="{{ $data->condition_paiement }}" required>
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
    <div class="d-flex justify-content-center">
        {{ $achats->links() }} <!-- Pagination -->
    </div>

</div>
@push('scripts')

    <script>


        function openEditModal(demandeId) {
            $('#editDemandeModal_' + demandeId).modal('show');
        }
    </script>
@endpush
@endsection