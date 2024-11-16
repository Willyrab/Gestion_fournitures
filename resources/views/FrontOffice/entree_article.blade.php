@extends('FrontOffice.layouts.app')

@section('title', 'Entree Article')
@section('head')
<link rel="stylesheet" href="{{ asset('css/demandeachat.css') }}">
@endsection

@section('content')
<div id="content" class="p-4 p-md-5">
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8"> <!-- Augmentez la largeur en utilisant col-md-8 -->
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


                <form class="p-4 shadow form-demande-achat" action="{{ route('insert.entreeArticle') }}" method="POST"
                    id="form-demande">
                    <h1 class="form-title">Entrée d'Article</h1>

                    @csrf
                    <div id="demandes-container">
                        <div class="demande-item">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="id_article">Article</label>
                                    <select class="form-control" name="id_article">
                                        @foreach($articles as $article)
                                            <option value="{{ $article->id_article }}">{{ $article->nom_article }} Ref
                                                {{ $article->reference }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="quantite">Quantité</label>
                                    <input type="number" class="form-control" id="quantite"
                                        placeholder="Entrez la quantité" name="quantite">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="prix">Prix(Unitaire en Ariary)</label>
                                    <input type="number" class="form-control" id="prix" placeholder="Entrez le prix"
                                        name="prix">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="id_fournisseur">Fournisseur</label>
                                    <select class="form-control" name="id_fournisseur">
                                        @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id_fournisseur }}">
                                                {{ $fournisseur->nom_fournisseur}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Bouton pour soumettre -->
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-warning">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection