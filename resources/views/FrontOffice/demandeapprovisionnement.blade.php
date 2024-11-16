@extends('FrontOffice.layouts.app')

@section('title', 'Demande Approvisionnement')

@section('head')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="title-container">
        <h1 class="ml-3 mb-0 display-4">Demande d' approvisionnement</h1>
        <hr>
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
    </div>

        <!-- Formulaire de recherche -->
    <div class="search-container mb-4">
        <form method="GET" action="{{ route('demande.aprovisonnement') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="reference_article" class="form-control" placeholder="Référence"
                        value="{{ request('reference_article') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="nom_article" class="form-control" placeholder="Nom"
                        value="{{ request('nom_article') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_demande" class="form-control" placeholder="Unité"
                        value="{{ request('date_demande') }}">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                    <a href="{{ route('demande.aprovisonnement') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>
                </div>
            
            </div>
        </form>
    </div>
    <!-- Tableau des résultats -->
    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Référence</th>
                <th scope="col">Article</th>
                <th scope="col">Date demande</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @if($demandeapprovisionnements->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">Aucun demande trouvée.</td>
                </tr>
            @else
                @foreach($demandeapprovisionnements as $demandeapprovisionnement)
                    <tr>
                        <td>{{ $demandeapprovisionnement->reference_article }}</td>
                        <td>{{ $demandeapprovisionnement->nom_article }}</td>
                        <td>{{ \Carbon\Carbon::parse($demandeapprovisionnement->date_demande)->format('d-m-Y') }}</td>
                        <td>
                            @if($demandeapprovisionnement->nom_status === 'En attente')
                                <span class="badge badge-danger">{{ $demandeapprovisionnement->nom_status }}</span>
                            @elseif($demandeapprovisionnement->nom_status === 'Validee')
                                <span class="badge badge-success">{{ $demandeapprovisionnement->nom_status }}</span>
                            @else
                                <span class="badge badge-warning">{{ $demandeapprovisionnement->nom_status }}</span>
                            @endif
                        </td>
                   
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
    {{ $demandeapprovisionnements->links() }} 
    </div>
    
</div>


@endsection



