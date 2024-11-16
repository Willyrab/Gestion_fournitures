@extends('FrontOffice.Departement.layouts.departement')
@section('title', 'Index')

@section('head')
    <meta name="description" content="Bienvenue chez Mada-Immo">
    <meta name="keywords" content="A propos de Mada-Immo">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')



<div class="intro-section">
    <div class="intro-text">
        <h1 class="intro-title">Bonjour et bienvenue !</h1>
        <h2 class="intro-subtitle">dans votre application de gestion de fournitures de bureau</h2>
        <p class="intro-paragraph">
            Facilitez vos besoins avec cette application moderne. Vous pouvez ici <strong>demander les articles</strong> dont votre département a besoin concernant les fournitures de bureau. De plus,
             il est également possible de <strong> demander un nouveau article</strong> qui n'est pas encore à l'usage de votre département.
        </p>
    </div>
    <div class="intro-image">
        <img src="{{ asset('images/departement.jpg') }}" alt="Agence Immobilier">
    </div>
</div>

@endsection
