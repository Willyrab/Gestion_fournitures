@extends('FrontOffice.layouts.app')

@section('title', 'Index | Manager')

@section('head')
<meta name="description" content="page pour voir loyer à payer ou payé entre 2 dates">
<meta name="keywords" content="Loyer à payer ou payé du client  ">
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection



@section('content')
<!-- Affichage des messages d'erreur -->

<div class="intro-section">
    <div class="intro-text">
        <h1 class="intro-title"> Bonjour et bienvenue ! </h1>
        <h2 class="intro-subtitle">Gestion des stocks de votre centre</h2>
        <p class="intro-paragraph">
            En tant que <strong>gestionnaire de centre</strong>, vous avez un accès direct et centralisé à la
            <strong>supervision des niveaux de stock</strong> et au suivi des besoins en fournitures pour chaque
            département.
            Votre rôle est essentiel pour assurer une <strong>disponibilité optimale des articles</strong> et anticiper
            les ruptures de stock. Vous pouvez facilement <strong>gérer les demandes d'approvisionnement et
                d'achat</strong>, vérifier les articles critiques, et suivre les nouvelles demandes d'articles.
            Avec notre interface, <strong>recherchez et mettez à jour les articles</strong>, suivez les consommations,
            et simplifiez le suivi des fournisseurs pour garantir un réapprovisionnement fluide et efficace.
        </p>
    </div>
    <div class="intro-image">
        <img src="{{ asset('images/gestionnaire.jpg') }}" alt="Gestion des fournitures">
    </div>
</div>



@endsection