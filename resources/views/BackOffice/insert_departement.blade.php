@extends('BackOffice.layouts.app')

@section('title', 'Creer departement')
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
                <form class="p-4 shadow form-demande-achat col-md-10" action="{{ route('traitement.departement')}}" method="POST"
                    id="form-demande">
                    <h1 class="form-title">Insertion d'epartement</h1>
                    @csrf

                    <!-- Container pour les ensembles dynamiques -->
                    <div id="demandes-container">
                        <!-- Première demande -->
                        <div class="demande-item">

                            <!-- Article et Quantité alignés côte à côte -->
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="nom">Departement</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Entrez le nom de departement" name="nom">
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