@extends('BackOffice.layouts.app')

@section('title', 'Demande de devis')
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
                <form class="p-4 shadow form-demande-achat" action="{{ route('traitement.demandedevis') }}"
                    method="POST" id="form-demande">
                    <h1 class="form-title">Demande de devis</h1>
                    @csrf

                    <!-- 1. Infos Fournisseurs -->
                    <div class="section-header">
                        <h4>Infos Fournisseurs</h4>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="nom_fournisseur">Nom du fournisseur</label>
                            <input type="text" class="form-control" placeholder="Entrer le nom du fournisseur"
                                name="nom_fournisseur" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email_fournisseur">Adresse email</label>
                            <input type="email" class="form-control" placeholder="Entrez l'email"
                                name="email_fournisseur" required>
                        </div>

                        <div class="col-md-6">
                            <label for="contact_fournisseur">Contact</label>
                            <input type="tel" class="form-control" placeholder="Entrez le contact"
                                name="contact_fournisseur" required>
                        </div>

                        <div class="col-md-6">
                            <label for="lieu_fournisseur">Lieu</label>
                            <input type="text" class="form-control" placeholder="Entrez le lieu" name="lieu_fournisseur"
                                required>
                        </div>
                    </div>

                    <!-- 2. Infos Demandeur -->
                    <div class="section-header mt-4">
                        <h4>Demandeur</h4>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="nom_demandeur">Nom du demandeur</label>
                            <input type="text" class="form-control" placeholder="Entrez le nom" name="nom_demandeur"
                                required>
                        </div>

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

                    <!-- 3. Infos Articles -->
                    <div class="section-header mt-4">
                        <h4>Articles</h4>
                    </div>

                    <div id="articles-container">
                        <div class="article-item">
                            <div class="header-actions">
                                <button type="button" class="btn-close remove-article"><i
                                        class="fas fa-times"></i></button>
                                <button type="button" class="btn-add add-article"><i class="fas fa-plus"></i></button>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="nom_article[]">Nom de l'article</label>
                                    <input type="text" class="form-control" placeholder="Entrez le nom de l'article"
                                        name="nom_article[]" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="quantite[]">Quantité</label>
                                    <input type="number" class="form-control" placeholder="Entrez la quantité"
                                        name="quantite[]" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="unite[]">Unité</label>
                                    <input type="text" class="form-control" placeholder="Entrez l'unité" name="unite[]"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <label for="description[]">Description</label>
                                    <textarea class="form-control" placeholder="Décrivez l'article" name="description[]"
                                        rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- 4. Autres Informations -->
                    <div class="section-header mt-4">
                        <h4>Autres Informations</h4>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
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

                        <div class="col-md-6">
                            <label for="date_limite">Date limite</label>
                            <input type="date" class="form-control" name="date_limite" required>
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
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sélectionner le conteneur des articles
            const articlesContainer = document.getElementById('articles-container');

            // Fonction pour ajouter un nouvel article
            function addArticle() {
                // Cloner le premier article
                const newArticle = articlesContainer.querySelector('.article-item').cloneNode(true);

                // Réinitialiser les valeurs des champs dans le nouvel article
                newArticle.querySelectorAll('input, select, textarea').forEach(function (input) {
                    input.value = '';
                });

                // Ajouter le nouvel article à la fin
                articlesContainer.appendChild(newArticle);

                // Réactiver les boutons dans le nouvel article
                attachEventsToArticle(newArticle);
            }

            // Fonction pour supprimer un article
            function removeArticle(event) {
                const article = event.target.closest('.article-item');
                if (articlesContainer.children.length > 1) {
                    article.remove();
                } else {
                    alert('Vous devez avoir au moins un article.');
                }
            }

            // Attacher les événements sur chaque article
            function attachEventsToArticle(article) {
                article.querySelector('.add-article').addEventListener('click', addArticle);
                article.querySelector('.remove-article').addEventListener('click', removeArticle);
            }

            // Attacher les événements sur le premier article
            attachEventsToArticle(articlesContainer.querySelector('.article-item'));
        });

    </script>
@endpush
@endsection