@extends('BackOffice.layouts.app')

@section('title', 'Demande Approvisionnement')

@section('head')
<link rel="stylesheet" href="{{ asset('css/recherche.css') }}">

@endsection
@section('content')
<div class="container mt-5">
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
    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Demandes d'approvisionnement</h1>

        <form method="GET" action=" {{ route('demande.approvisionnement.admin') }}" class="d-flex">
            <!-- Category Select Dropdown -->
            <div class="col-md-8">
                <select class="form-control " name="poste">
                    <option value="">-- Sélectionnez un poste --</option>
                    @foreach($postes as $poste)
                        <option value="{{ $poste->id_poste }}" {{ (request('poste') == $poste->id_poste) ? 'selected' : '' }}>
                            {{ $poste->nom_poste }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
            </div>
        </form>
    </div>

    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Référence</th>
                <th scope="col">Nom</th>
                <th scope="col">Description</th>
                <th scope="col">Date de demande</th>
                <th scope="col">Poste</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if($approvisionnementavalider->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">Aucun article trouvé.</td>
                </tr>
            @else
                @foreach($approvisionnementavalider as $demandeapprovisionnement)
                    <tr>
                        <td>{{ $demandeapprovisionnement->reference_article}}</td>
                        <td>{{ $demandeapprovisionnement->nom_article }}</td>
                        <td>{{ $demandeapprovisionnement->description}}</td>
                        <td>{{ \Carbon\Carbon::parse($demandeapprovisionnement->date_demande)->format('d-m-Y') }} </td>
                        <td> {{ $demandeapprovisionnement->nom_poste}}</td>
                        <td>
                        @if($demandeapprovisionnement->id_status === 1)
                                <form action="{{ route('demande.valider', $demandeapprovisionnement->id_demandeapp) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success w-100 ">Valider la demande</button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" disabled>Déjà validé</button>
                            @endif
                        </td>

                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
   

        <div class="d-flex justify-content-center">
            {{ $approvisionnementavalider->links() }}
        </div>
 
</div>
@endsection