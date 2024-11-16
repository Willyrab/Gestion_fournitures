@extends('FrontOffice.Departement.layouts.departement')

@section('title', 'Demande Besoins')
@section('head')
<link rel="stylesheet" href="{{ asset('css/departement.css') }}">
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <!-- Affichage des erreurs de validation -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mt-5">
                <div class="card-header">
                    <h3>Demande d'article</h3>
                </div>
                <hr>
                <div class="card-body">


                    <!-- Formulaire de connexion -->
                    <form action="{{ route('traitement_besoins.departement')}} " method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nom">Article</label>
                            <div class="input-group">

                                <select class="form-control" name="id_article">
                                    @foreach($articles as $article)
                                        <option value="{{ $article->id_article }}">{{ $article->nom_article }} Ref
                                            {{ $article->reference }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Quantité(e/s) </label>
                            <div class="input-group">

                                <input type="number" class="form-control" id="quantite" name="quantite"
                                    placeholder="Entrez la quantité" required>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary btn-block">
                            Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection