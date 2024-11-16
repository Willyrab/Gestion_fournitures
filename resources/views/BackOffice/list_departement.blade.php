@extends('BackOffice.layouts.app')

@section('title', 'Listes Departement')

@section('head')
<link rel="stylesheet" href="{{ asset('css/list.min.css') }}">

@endsection
@section('content')
<div class="container mt-5">

    <div class="title-container">
        <h1 class="display-4">Liste des Departements</h1>
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

    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nom Departement</th>
                <!-- Ajoute d'autres colonnes selon les données dans ta vue -->
            </tr>
        </thead>
        <tbody>
            @if($departements->isEmpty())
                <tr>
                    <td colspan="10" class="text-center">Aucun reponse trouvé.</td>
                </tr>
            @else
                @foreach($departements as $departement)
                    <tr>
                        <td>{{ $departement->nom_departement }}</td>

                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>


</div>

@push('scripts')
<script>

function setWithExpiry(key, value, ttl) {
    const now = new Date();

    
    const item = {
        value: value,
        expiry: now.getTime() + ttl,
    };
    localStorage.setItem(key, JSON.stringify(item));
}

function getWithExpiry(key) {
    const itemStr = localStorage.getItem(key);
    
    
    if (!itemStr) {
        return null;
    }

    const item = JSON.parse(itemStr);
    const now = new Date();

    // Comparer l'expiry time avec le temps actuel
    if (now.getTime() > item.expiry) {
        // Si l'item est expiré, le supprimer du storage
        localStorage.removeItem(key);
        return null;
    }
    return item.value;
}
    $(document).ready(function() {
    console.log('jQuery is working inside document ready!');

    const storageKey = 'departements';
    const ttl = 1000 * 60 * 60 * 24; // 24 heures en millisecondes

    //Verification du stockage local
    let departements = getWithExpiry(storageKey);
    if (!departements) {
        departements = @json($departements); 
        setWithExpiry(storageKey, departements, ttl);
        console.log('Locations stored in localStorage:', departements);
    } else {
        console.log('Locations loaded from localStorage:', departements);
    }
});
</script>
@endpush
@endsection