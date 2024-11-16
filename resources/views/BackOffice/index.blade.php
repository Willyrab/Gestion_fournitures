@extends('BackOffice.layouts.app')
@section('title', 'Index')

@section('head')
    <meta name="description" content="Bienvenue chez Mada-Immo">
    <meta name="keywords" content="A propos de Mada-Immo">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<!-- Affichage des messages de succès -->


<div class="intro-section">
    <div class="intro-text">
        <h1 class="intro-title"> Bonjour et bienvenue ! </h1>
        <h2 class="intro-subtitle">Gestion des fournitures de bureau</h2>
        <p class="intro-paragraph">
            Nous sommes spécialisés dans la <strong>gestion des approvisionnements</strong> et le <strong>suivi des stocks de fournitures de bureau</strong>.
            Notre mission est de soutenir les services de l'entreprise avec une <strong>gestion efficace et centralisée</strong> des articles.
            Que ce soit pour <strong>valider les demandes d'achat, gérer les bons de commande et devis, ou comparer les fournisseurs</strong>, notre équipe assure une organisation sans faille.
            Nous optimisons les processus de commande et d'approvisionnement pour améliorer l'efficacité et la satisfaction des utilisateurs. Faites confiance à notre expertise pour une gestion des fournitures proactive et fluide.
        </p>
    </div>
    <div class="intro-image">
        <img src="{{ asset('images/admin.jpg') }}" alt="Gestion des fournitures">
    </div>
</div>


@endsection
