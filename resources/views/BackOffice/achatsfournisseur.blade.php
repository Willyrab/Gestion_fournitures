@extends('BackOffice.layouts.app')

@section('title', 'Creation BC')
@section('head')
<link rel="stylesheet" href="{{ asset('css/demandeachat.css') }}">

@endsection
@section('content')
<div id="content" class="p-4 p-md-5">

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
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

                <!-- Formulaire Demande Achat -->
                <form class="p-4 shadow form-demande-achat" action="{{ route('traitement.achatfournisseur') }}"
                    method="POST" id="form-demande">
                    <h1 class="form-title">Création bon de commande</h1>
                    @csrf
                    <div class="section-header">
                        <h4>Informations Acheteur</h4>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <label for="nom_demandeur">Nom d'acheteur</label>
                            <input type="text" class="form-control" placeholder="Entrez le nom" name="nom_demandeur"
                                required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="email_demandeur">Email du demandeur</label>
                            <input type="email" class="form-control" placeholder="Entrez l'email" name="email_demandeur"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="contact_demandeur">Contact du demandeur</label>
                            <input type="tel" class="form-control" placeholder="Entrez le contact"
                                name="contact_demandeur" required>
                        </div>
                    </div>

                    <div class="section-header mt-4">
                        <h4>Envoyer à</h4>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <label for="id_poste">Poste</label>
                            <select class="form-control" name="id_poste" required>
                                <option value="">Sélectionnez le poste</option>
                                @foreach($postes as $poste)
                                    <option value="{{ $poste->id_poste }}">{{ $poste->nom_poste }} Ref
                                        {{ $poste->lieu_poste }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                      
                    </div>

                    <div class="section-header mt-4">
                        <h4>Informations Fournisseurs</h4>
                    </div>
                    <div class="form-row">
                    <div class="col-md-12">
                        <label for="fournisseur">Fournisseurs</label>
                        <select class="form-control" id="fournisseur-select" name="fournisseur" required>
                            <option value="">Sélectionnez un fournisseur</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id_fournisseur }}">{{ $fournisseur->nom_fournisseur }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    
                    <div class="section-header mt-4">
                        <h4>Informations de paiement</h4>
                    </div>
                    <div class="form-row">
                     

                        <div class="col-md-12">
                            <label for="condition_paiement">Condition de paiement</label>
                            <input type="tel" class="form-control" placeholder="Entrez la condition de paiement"
                                name="condition_paiement" required>
                        </div>
                      
                    </div>
                    <div class="form-row">
                       
                        <div class="col-md-6">
                            <label for="lieu_livraison">Lieu de livraison</label>
                            <input type="text" class="form-control" placeholder="Entrez le lieu de livraison" name="lieu_livraison"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="date_livraison">Date de livraison</label>
                            <input type="date" class="form-control" placeholder="Entrez la date de livraison"
                                name="date_livraison" required>
                        </div>
                    </div>
                    <div class="section-header mt-4">
                        <h4>Informations Articles</h4>
                    </div>

                    <div id="demandes-container">
                        <!-- Première demande -->
                        <div class="demande-item">
                            <!-- En-tête avec les boutons + et x -->
                            <div class="header-actions">
                                <button type="button" class="btn-close"><i class="fas fa-times"></i></button>
                                <button type="button" class="btn-add"><i class="fas fa-plus"></i></button>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="id_article[]">Article</label>
                                    <select class="form-control" id="article-select" name="id_article[]" required>
                                        <option value="">Sélectionnez un article</option>
                                        @foreach($articles as $article)
                                            <option value="{{ $article->id_article }}">
                                                {{ $article->nom_article }} réf: {{ $article->reference }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="prix[]">Prix</label>
                                    <input type="number" class="form-control" placeholder="Entrez le prix" name="prix[]"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quantite[]">Quantité</label>
                                    <input type="number" class="form-control" placeholder="Entrez la quantité"
                                        name="quantite[]" required>
                                </div>
                            </div>


                        </div>

                    </div>
                    <!-- Bouton pour soumettre -->
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-warning">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script pour ajouter/retirer des champs dynamiques -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const demandesContainer = document.getElementById('demandes-container');

            // Fonction pour ajouter une nouvelle demande
            function ajouterDemande() {
                const demandeItem = document.createElement('div');
                demandeItem.classList.add('demande-item');
                demandeItem.innerHTML = `
                            <div id="demandes-container">
                                    <!-- Première demande -->
                                    <div class="demande-item">
                                        <!-- En-tête avec les boutons + et x -->
                                        <div class="header-actions">
                                            <button type="button" class="btn-close"><i class="fas fa-times"></i></button>
                                            <button type="button" class="btn-add"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <label for="id_article[]">Article</label>
                                                <select class="form-control" id="article-select" name="id_article[]" required>
                                                    <option value="">Sélectionnez un article</option>
                                                    @foreach($articles as $article)
                                                        <option value="{{ $article->id_article }}">
                                                            {{ $article->nom_article }} réf: {{ $article->reference}}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label for="prix[]">Prix</label>
                                                <input type="number" class="form-control" placeholder="Entrez le prix" name="prix[]"
                                                    required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="quantite[]">Quantité</label>
                                                <input type="number" class="form-control" placeholder="Entrez la quantité"
                                                    name="quantite[]" required>
                                            </div>
                                        </div>


                                    </div>

                                </div>

                                                                    `;
                demandesContainer.appendChild(demandeItem);

                // Ajouter les événements pour les nouveaux boutons
                attacherEvenements(demandeItem);
            }

            // Fonction pour supprimer une demande
            function supprimerDemande(element) {
                if (demandesContainer.childElementCount > 1) {
                    element.remove();
                }
            }

            // Attacher les événements pour ajouter et supprimer des demandes
            function attacherEvenements(demandeItem) {
                const btnAdd = demandeItem.querySelector('.btn-add');
                const btnClose = demandeItem.querySelector('.btn-close');

                btnAdd.addEventListener('click', function () {
                    ajouterDemande();
                });

                btnClose.addEventListener('click', function () {
                    supprimerDemande(demandeItem);
                });
            }

            // Attacher les événements au premier ensemble de champs
            const premiereDemande = document.querySelector('.demande-item');
            attacherEvenements(premiereDemande);
        });


    </script>
@endpush

@endsection