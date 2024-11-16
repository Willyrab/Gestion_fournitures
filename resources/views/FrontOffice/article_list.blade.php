@extends('FrontOffice.layouts.app')
@section('title', 'Liste des Articles')

@section('head')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="title-container">
        <h1 class="display-4">Liste des Articles</h1>
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
        <form method="GET" action="{{ route('articles.list') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="reference" class="form-control" placeholder="Référence"
                        value="{{ request('reference') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="nom_article" class="form-control" placeholder="Article"
                        value="{{ request('nom_article') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="categorie">
                        <option value="">- Sélectionner une catégorie -</option>
                        @foreach($categories as $art)
                            <option value="{{ $art->id_categorie }}" {{ request('categorie') == $art->id_categorie ? 'selected' : '' }}>
                                {{ $art->nom_categorie }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2">Rechercher</button>
                    <a href="{{ route('articles.list') }}" class="btn btn-reset"> <i class="fas fa-redo"></i></a>
                </div>

            </div>
        </form>
    </div>


    <!-- Tableau des résultats -->
    <table class="table table-striped table-bordered" id="resultsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Référence</th>
                <th scope="col">Nom</th>
                <th scope="col">Description</th>
                <th scope="col">Unité</th>
                <th scope="col">Categorie</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if($articles->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">Aucun article trouvé.</td>
                </tr>
            @else
                @foreach($articles as $article)
                    <tr>
                        <td>{{ $article->reference }}</td>
                        <td>{{ $article->nom_article }}</td>
                        <td>{{ $article->description }}</td>
                        <td>{{ $article->unite }}</td>
                        <td>{{ $article->categorie->nom_categorie }}</td>
                        <td>
                            <button type="button" class="btn btn-outline-success edit-article-btn"
                                onclick="openEditModal({{ $article->id_article }}); event.stopPropagation();">
                                <i class="fa fa-edit"></i>&nbsp; Modifier
                            </button>
                        </td>

                    </tr>

                    <!-- Modale pour modifier un article -->
                    <div class="modal fade" id="editArticleModal_{{ $article->id_article }}" tabindex="-1" role="dialog"
                        aria-labelledby="editArticleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editArticleModalLabel">Modifier l'article</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('article.update', $article->id_article) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" id="articleId" name="articleId">

                                        <div class="form-group">
                                            <label for="reference">Référence</label>
                                            <input type="text" class="form-control" id="reference" name="reference"
                                                value="{{ $article->reference}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="nom_article">Nom</label>
                                            <input type="text" class="form-control" id="nom_article" name="nom_article"
                                                value="{{ $article->nom_article}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description"
                                                rows="3">{{ $article->description }}</textarea>

                                        </div>

                                        <div class="form-group">
                                            <label for="unite">Unité</label>
                                            <input type="text" class="form-control" id="unite" name="unite"
                                                value="{{ $article->unite}}" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Enregistrer les
                                            modifications</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $articles->links() }}
    </div>
</div>
@push('scripts')
    <script>
        function openEditModal(articleId) {
            $('#editArticleModal_' + articleId).modal('show');
        }
    </script>
@endpush
@endsection