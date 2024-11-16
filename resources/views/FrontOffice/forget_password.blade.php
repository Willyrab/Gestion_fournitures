<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recuperer mot de passe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <img src="{{asset('images/IMG_3499.png')}}" alt="Ultimate Team Logo" class="logo">
                        <h3>Récupération mot de passe</h3>
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

                        <!-- Formulaire de connexion -->
                        <form action="{{ route('sendmdp.manager')}} " method="POST">
                        @csrf
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


                            <div class="checkbox">
                               
                                <label class="pull ">
                                    <a href="{{ route('login.manager')}}">Annuler</a>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Recupérer mon compte
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