@extends('FrontOffice.layouts.app')

@section('title', 'Insert Founrnisseur')
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

             
                <form class="p-4 shadow form-demande-achat" action="{{route('create.fournisseurs')}}" method="POST"
                    id="form-demande">
                    <h1 class="form-title">Insertion Fournisseur</h1>
                    @csrf

                    <div id="demandes-container">
                   
                        <div class="demande-item">

                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Entrez le nom de Fournisseur" name="nom">
                                </div>

                            </div>

                            <div class="mt-4 mb-3 row">
                                <div class="col-md-12">
                                    <label for="nom">Lieu</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Entrez le lieu de Fournisseur" name="lieu">
                                </div>
                            </div>

                            <div class="mt-4 mb-3 row">
                                <div class="col-md-12">
                                    <label for="description">Adresse electronique</label>
                                    <input type="email" class="form-control" id="" placeholder="fournisseur@gmail.com"
                                        name="email">
                                   
                                </div>
                            </div>
                            <div class="mt-4 mb-3 row">
                                <div class="col-md-12">
                                    <label for="unite">Contact</label>
                                    <input type="number" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Entrez ici le contact" name="contact">
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