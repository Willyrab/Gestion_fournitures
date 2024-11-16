@extends('FrontOffice.layouts.app')

@section('title', 'Demande Achat')
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
                <form class="p-4 shadow form-demande-achat" action="{{ route('traitement.demandeachat') }}"
                    method="POST" id="form-demande">
                    <h1 class="form-title">Demande d'Achat de nouveau artcile</h1>
                    @csrf

                    <!-- Container pour les ensembles dynamiques -->
                    <div id="demandes-container">
                        <!-- Première demande -->
                        <div class="demande-item">
                            <!-- En-tête avec les boutons + et x -->
                            <div class="header-actions">
                                <button type="button" class="btn-close"><i class="fas fa-times"></i></button>
                                <button type="button" class="btn-add"><i class="fas fa-plus"></i></button>
                            </div>

                            <!-- Article et Quantité alignés côte à côte -->
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="id_nouveaubesoins[]">Article</label>
                                    <select class="form-control" name="id_nouveaubesoins[]">
                                        @foreach($articles as $article)
                                            <option value="{{ $article->id_nouveaubesoins }}">{{ $article->nom_article }}
                                            </option>
                                        @endforeach
                                    </select>
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
                        <button type="submit" class="btn btn-warning">Envoyer</button>
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
                            <div class="header-actions">
                                <button type="button" class="btn-close"><i class="fas fa-times"></i></button>
                                <button type="button" class="btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                <label for="id_nouveaubesoins[]">Article</label>
                                <select class="form-control" name="id_nouveaubesoins[]">
                                        @foreach($articles as $article)
                                            <option value="{{ $article->id_nouveaubesoins }}">{{ $article->nom_article }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quantite[]">Quantité</label>
                                    <input type="number" class="form-control" placeholder="Entrez la quantité" name="quantite[]" required>
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