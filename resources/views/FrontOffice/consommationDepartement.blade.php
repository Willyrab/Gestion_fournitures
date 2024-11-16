@extends('FrontOffice.layouts.app')

@section('title', 'Prévision des articles')

@section('head')
<link rel="stylesheet" href="{{ asset('css/suivis.css') }}">
@endsection

@section('content')
<div class="container mt-5">
<div class="title-container">
        <h1 class="display-4">Consommations département</h1>
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
<strong class="text-muted">Rechercher les consommations d'article entre deux dates :</strong>

        <form method="GET" action="{{ route('consommation.departement') }}">
            <div class="row">
                
                <div class="col-md-3">
                    
                    <input type="date" name="date_debut" class="form-control" placeholder="Date de début"
                        value="{{ request('date_debut') }}">

                </div>
                <div class="col-md-3">
                    <input type="date" name="date_fin" class="form-control" placeholder="Date de fin"
                        value="{{ request('date_fin') }}">

                </div>
                <div class="col-md-3">
                    <select class="form-control " name="article">
                        <option value="">-- Sélectionner un article --</option>
                        @foreach($articles as $art)
                            <option value="{{ $art->nom_article }}" {{ request('article') == $art->nom_article ? 'selected' : '' }}>
                                {{ $art->nom_article }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                    <a href="{{ route('consommation.departement') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>
                </div>

            </div>
        </form>
    </div>

    <!-- Affichage des consommations -->
    @if($consommations->isEmpty())
        <div class="alert alert-warning text-center">Aucune consommation trouvée pour cet article.</div>
    @else
        <!-- Tableau des consommations -->
        <h1 class="information-title">Informations détaillées</h1>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nom Article</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                    <th>Nom Département</th>
                    <th>Date Sortie</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consommations as $consommation)
                    <tr>
                        <td>{{ $consommation->nom_article }}</td>
                        <td>{{ $consommation->quantite }}</td>
                        <td>{{ $consommation->unite }}</td>
                        <td>{{ $consommation->nom_departement }}</td>
                        <td>{{ \Carbon\Carbon::parse($consommation->date_sortie)->format('d-m-Y') }}</td>
                        <td>{{ $consommation->nom_responsable }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $consommations->links() }}
        </div>
<h1 class="graph-title">Représentation Graphique</h1>
        <canvas id="consommationChart" width="500" height="500"></canvas>

    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 
    <script>
        var ctx = document.getElementById('consommationChart').getContext('2d');

        // Plugin pour afficher les pourcentages sur chaque section du diagramme
        Chart.register({
            id: 'percentagePlugin',
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach(function(element, index) {
                        // Calcule le pourcentage
                        const data = dataset.data;
                        const total = data.reduce((acc, curr) => acc + curr, 0);
                        const percentage = Math.round((data[index] / total) * 100);

                        // Position du texte
                        const position = element.tooltipPosition();
                        ctx.fillStyle = 'black';
                        ctx.font = 'bold 12px Arial';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(percentage + '%', position.x, position.y);
                    });
                });
            }
        });

        var consommationChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($groupedData->keys()) !!}, // Noms des départements
                datasets: [{
                    label: 'Consommations par département',
                    data: {!! json_encode($groupedData->values()) !!}, // Quantités par département
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Assurez-vous que le graphique reste compact
                plugins: {
                    legend: {
                        position: 'right', // Place la légende à droite
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                var dataset = tooltipItem.dataset;
                                var total = dataset.data.reduce(function(previousValue, currentValue) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[tooltipItem.dataIndex];
                                var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                return currentValue + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            },
            plugins: ['percentagePlugin'] // Utilise le plugin pourcentages
        });
    </script>

@endpush
@endsection