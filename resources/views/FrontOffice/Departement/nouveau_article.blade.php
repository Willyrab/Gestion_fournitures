@extends('FrontOffice.Departement.layouts.departement')

@section('title', 'Demande Nouveau Article')
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
                    <h3>Demande Nouveau Article</h3>
                </div>
                <hr>
               
                <div class="card-body">
                
                    <!-- Formulaire de connexion -->
                    
                    <form action="{{ route('traitement_nouveaubesoins.departement')}} " method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nom">Article</label>
                            <div class="input-group">

                                <input type="text" class="form-control" id="article" name="nom_article"
                                    placeholder="Nom d'article" required>
                                </select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">description</label>
                            <textarea class="form-control" placeholder="Décrivez l'article" name="description" rows="3"
                                required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="quantite">Quantité(e/s) </label>
                            <div class="input-group">

                                <input type="number" class="form-control" id="quantite" name="quantite"
                                    placeholder="Entrez la quantité" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="unite">Unité </label>
                            <div class="input-group">

                                <input type="text" class="form-control" id="unite" name="unite"
                                    placeholder="Entrez l'Unité d'article" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="motifs">Motifs </label>
                            <div class="input-group">

                            <textarea class="form-control" placeholder="Motifs du demande" name="motifs" rows="3"
                                required></textarea>
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