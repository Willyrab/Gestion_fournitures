@extends('BackOffice.layouts.app')

@section('title', 'Prévision des articles')

@section('head')
<link rel="stylesheet" href="{{ asset('css/new.css') }}">
@endsection

@section('content')
<div class="container mt-5">

    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Prévision des articles</h1>

        <!-- Formulaire de sélection de poste et d'article -->
        <form method="GET" action="" class="d-flex">
            <div class="col-md-5">
                <!-- Sélection de poste -->
                <select class="form-control" name="poste" onchange="this.form.submit()">
                    <option value="">-- Sélectionner un poste --</option>
                    @foreach($postes as $poste)
                        <option value="{{ $poste->id_poste }}" {{ request('poste') == $poste->id_poste ? 'selected' : '' }}>
                            {{ $poste->nom_poste }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Sélection d'article basé sur le poste sélectionné -->
            <div class="col-md-5 ">
                <select class="form-control" name="article">
                    <option value="">-- Sélectionner un article --</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->nom_article }}">
                            {{ $article->nom_article }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
            </div>
        </form>
    </div>

    <canvas id="consommationChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('consommationChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json(array_keys(collect($historique)->toArray())), // Assurez-vous que $historique est bien une collection
            datasets: [{
                label: 'Consommation Historique',
                data: @json(collect($historique)->map(function ($donnees) {
                    return array_sum(array_column($donnees, 'quantite')); // Somme des quantités sorties pour chaque mois
                })->values()->all()), // Transformer en tableau de valeurs
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantité (unités)'
                    }
                }
            }
        }
    });
</script>

    <!-- Prévision pour le mois suivant -->
    <div class="mt-5">
        <h3>Prévision pour le mois suivant :</h3>
        <p>Nous estimons qu'une commande de <strong style="color: red;">{{ $previsionArrondie }} {{ $unite }} de {{ $selectedArticle }}</strong> sera nécessaire pour le mois prochain.</p>
    </div>
@endsection
