@extends('FrontOffice.layouts.app')

@section('title', 'Stock Critique')

@section('head')
<link rel="stylesheet" href="{{ asset('css/critique.css') }}">
@endsection



@section('content')
<div class="container mt-5">

    <div class="title-container">
    
        <!-- Affichage des messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        <h1 class="ml-3 mb-0 display-4">Articles nécessitant un approvisionnement</h1>
        <hr>
    </div>



    <!-- Tableau des résultats -->
    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Référence</th>
                <th scope="col">Article</th>
                <th scope="col">Stock actuel</th>
                <th scope="col">Unité</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($stocks->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">Aucun article trouvé.</td>
                </tr>
            @else
                @foreach($stocks as $stock)
                    <tr>
                        <td>{{ $stock->reference_article }}</td>
                        <td>{{ $stock->nom_article }}</td>
                        <td>{{ $stock->stock_actuel }}</td>
                        <td>{{ $stock->unite }}</td>
                        <td>
                            <form method="POST" action="{{ route('demande.approvisionnement') }}">
                                @csrf
                                <input type="hidden" name="id_article" value="{{ $stock->id_article }}">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fa fa-cart-arrow-down mr-3"></i>&nbsp; Demander un approvisionnement
                                </button>
                            </form>
                        </td>

                    </tr>


                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection