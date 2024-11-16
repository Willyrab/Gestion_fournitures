<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inscription | Département</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <img src="{{asset('images/IMG_3499.png')}} " alt="HITA Logo" class="logo mb-3">
                        <h3>Enregistrement Département</h3>
                    </div>
                    <div class="card-body">
                        <!-- Affichage des messages d'erreur de session -->
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Affichage des erreurs de validation -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Formulaire d'inscription -->
                        <form action="{{ route('departement.registration') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nom">Nom</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="nom" id="nom" class="form-control"
                                                placeholder="Entrez votre nom" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                            </div>
                                            <input type="email" name="email" id="email" class="form-control"
                                                placeholder="Entrez votre email" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Mot de passe</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="Entrez votre mot de passe" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmer le mot de passe</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation" class="form-control"
                                                placeholder="Confirmez votre mot de passe" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Row for "Poste" and "Département" on the same line -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="post">Poste</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-briefcase"></i>
                                                </span>
                                            </div>
                                            <select name="id_poste" id="post" class="form-control" required>
                                                @foreach ($postes as $poste)
                                                    <option value="{{ $poste->id_poste }}" {{ old('id_poste') == $poste->id_poste ? 'selected' : '' }}>
                                                        {{ $poste->nom_poste }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="departement">Département</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-building"></i>
                                                </span>
                                            </div>
                                            <select name="id_departement" id="departement" class="form-control" required>
                                                @foreach ($departements as $departement)
                                                    <option value="{{ $departement->id_departement }}" {{ old('id_departement') == $departement->id_departement ? 'selected' : '' }}>
                                                        {{ $departement->nom_departement }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="checkbox">
                                <label class="pull-right">
                                    <a href="{{ route('login.departement') }}">Vous avez déjà un compte!</a>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-user-plus"></i> S'inscrire
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
