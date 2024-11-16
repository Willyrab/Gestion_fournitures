@extends('FrontOffice.layouts.app')

@section('title', 'Insert Article')
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
                <form class="p-4 shadow form-demande-achat" action="{{route('create.articles')}}" method="POST"
                    id="form-demande">
                    <h1 class="form-title">Insertion d'Article</h1>
                    @csrf

                    <!-- Container pour les ensembles dynamiques -->
                    <div id="demandes-container">
                        <!-- Première demande -->
                        <div class="demande-item">


                            <!-- Article et Quantité alignés côte à côte -->
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="nom">Article</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Entrez le nom d'article" name="nom">
                                </div>
                                <div class="col-md-6">
                                    <label for="nom">Référence</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Entrez la référence d'article" name="reference">
                                </div>
                            </div>

                            <!-- Motifs/Description -->
                            <div class="form-group mt-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" placeholder="Décrivez l'article" name="description"
                                    rows="3" required></textarea>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="unite">Unité</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Exemple : paquet, boite..." name="unite">
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="id_categorie">Categorie</label>
                                    <select class="form-control" name="id_categorie">
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie->id_categorie }}">{{ $categorie->nom_categorie}}
                                            </option>
                                        @endforeach
                                    </select>
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

@endsection