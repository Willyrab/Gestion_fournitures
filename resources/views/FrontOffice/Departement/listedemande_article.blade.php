@extends('FrontOffice.Departement.layouts.departement')

@section('title', 'Listes demande articles')

@section('head')
<link rel="stylesheet" href="{{ asset('css/new.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="search-container p-4 mb-4 bg-light rounded d-flex align-items-center justify-content-between">
        <h1 class="ml-3 mb-0 display-4">Voir les demandes effectuées</h1>

        <form method="GET" action="{{ route('liste_besoins.departement')}}" class="d-flex">
            <div class="col-md-4">
                <input type="date" name="date_demande" class="form-control" placeholder="Rechercher par date"
                    value="{{ request('date_demande') }}">
            </div>

         
            <div class="col-md-4">
                        <select class="form-control" name="status">
                            <option value="">--Status --</option>
                            <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>En attente</option>
                            <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Valide</option>
                        </select>
             </div>

            <div class="col-md-2 d-flex align-items-center">
               
            <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
            <a href="{{ route('liste_besoins.departement') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>
           
        </div>
        </form>
    </div>

    @if($demandes->isEmpty())
        <div class="alert alert-warning text-center">Aucune demande trouvée.</div>
    @else
        <div class="accordion" id="accordionExample">
            @foreach($demandes as $index => $data)
                <div class="card">
                    <div class="card-header" id="heading{{ $index }}">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                                {{ $data->article->nom_article }} ({{ $data->article->reference }})
                            </button>
                        </h2>
                    </div>

                    <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p><strong>Description:</strong> {{ $data->article->description }}</p>
                            <p><strong>Quantité:</strong> {{ $data->quantite }} ({{ $data->article->unite }})</p>
                            <p><strong>Date de demande:</strong> {{ \Carbon\Carbon::parse($data->date_demande)->format('d-m-Y') }}</p>
                            <p><strong>Statut:</strong>
                
                             <span
                                    class="badge badge-{{ $data->status->nom_status === 'Validee' ? 'success' : ($data->status->nom_status === 'En attente' ? 'danger' : 'warning') }}">
                                    {{ $data->status->nom_status }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $demandes->links() }} <!-- Pagination -->
        </div>
    @endif
</div>
@endsection