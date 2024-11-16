@extends('FrontOffice.layouts.app')

@section('title', 'Stock Actuel')

@section('head')
<link rel="stylesheet" href="{{ asset('css/stockactuel.css') }}">
@endsection

@section('content')
<div class="container mt-5">



    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Niveaux de stocks par catégorie</h1>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('stock.actuel') }}" class="d-flex">
            <!-- Category Select Dropdown -->
            <div class="col-md-8">
                <select class="form-control" name="id_categorie">
                    <option value="">-- Sélectionnez une catégorie --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id_categorie }}" {{ request('id_categorie') == $categorie->id_categorie ? 'selected' : '' }}>
                            {{ $categorie->nom_categorie }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
            </div>
        </form>
        <!-- Title/Label Next to Search Bar -->
    </div>


    <!-- Stock Results -->
    @if($stocks->isNotEmpty())
        <div class="row">
            @foreach($stocks as $stock)
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-custom text-white shadow-sm">
                        <div class="card-body">
                            <h4 class="font-weight-normal mb-3">{{ $stock->nom_article }} ({{ $stock->reference_article }})</h4></h4>
                            <h2 class="font-weight-normal mb-5">{{ $stock->stock_actuel }} {{ $stock->unite }}(s)</h2>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @if(request()->has('id_categorie'))
          
            <div class="alert alert-warning text-center">Aucun article trouvé pour cette catégorie.</div>
        @else
            <!-- Afficher un message si aucune catégorie n'est choisie ou si le stock est vide -->
            <p>Aucun article trouvé.</p>
        @endif
    @endif

</div>
@endsection