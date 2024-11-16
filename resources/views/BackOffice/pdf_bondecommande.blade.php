<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bon de Commande - {{ $reference }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            color: #000;
            position: relative;
            background-color: #fff;
        }

        .container {
            width: 95%;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img.logo-small {
            max-width: 80px;
            margin-right: 10px;
        }

        .company-name {
            font-size: 30px;
            color: green;
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            flex-grow: 1;
            margin-top: -55px;
        }

        .info-section {
            width: 100%;
            margin-top: 20px;
            border-spacing: 20px;
        }

        .info-section td {
            vertical-align: top;
        }

        .company-info {
            font-size: 14px;
            line-height: 1.2;
        }

        .company-info strong {
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
            /* Empêche la table de se couper si possible */
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #000;
        }

        th {

            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
        }

        .signature-section {
            width: 100%;
            margin-top: 50px;
            border-spacing: 50px;
            page-break-inside: avoid;
            /* Empêche la coupure des signatures */
        }

        .signature-section td {
            text-align: center;
            padding-top: 40px;
        }

        .signature-box {
            padding-top: 10px;
            border-top: none;
            /* Supprime les bordures autour des signatures */
        }

        /* Logo en arrière-plan avec opacité */
        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ public_path('images/hita_logo.jpg') }}');
            background-size: 80%;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.1;
            /* Opacité ajustée pour ne pas interférer avec le texte */
            z-index: -1;
        }

        @media print {

            body,
            .container {
                page-break-inside: avoid;
                /* Aide à garder la mise en page propre */
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header avec logo et nom de l'entreprise centré -->
        <div class="header">
            <img src="{{ public_path('images/hita_logo.jpg') }}" alt="Logo HITA" class="logo-small">
            <div class="company-name">Huillerie Industrielle de Tamatave</div>
        </div>

        <!-- Informations de l'entreprise -->
        <div class="company-info">
            <strong>NIF :</strong> 3000063634<br>
            <strong>STAT :</strong> 463013120000000132<br>
            <strong>RCS :</strong> 2000B00066<br>
            <strong>Tél :</strong> 53 338 55 - 53 338 57<br>
            <strong>Adresse :</strong> Andoranga, Port Fluvial<br>
            <strong>Ville :</strong> TOAMASINA 501
        </div>

        <!-- Section avec les informations du fournisseur et de l'acheteur -->
        <table class="info-section">
            <tr>
                <td class="company-info">
                    <strong>Informations Fournisseur :</strong><br>
                    <strong>Nom :</strong> {{ $fournisseurs['nom_fournisseur'] }}<br>
                    <strong>Lieu :</strong> {{ $fournisseurs['lieu'] }}<br>
                    <strong>Email :</strong> {{ $fournisseurs['email_fournisseur'] }}<br>
                    <strong>Téléphone :</strong> {{ $fournisseurs['telephone'] }}
                </td>
                <td class="company-info">
                    <strong>Informations Acheteur :</strong><br>
                    <strong>Nom :</strong> {{ $acheteur['nom'] }}<br>
                    <strong>Poste :</strong> {{ $acheteur['poste'] }} <br>
                    <strong>Email :</strong> {{ $acheteur['email'] }}<br>
                    <strong>Téléphone :</strong> {{ $acheteur['telephone'] }}
                </td>
            </tr>
        </table>

        <div class="title">BON DE COMMANDE</div>
        <p><strong>Date :</strong>
            <span>{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}</span>
        </p>
        <p><strong>Référence :</strong> <span>{{ $reference }}</span></p>

        <!-- Tableau des articles -->
        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>TVA</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalTTCGlobal = 0;
                @endphp
                @foreach ($articles as $article)
                                @php
                                    $totalHT = $article->quantite * $article->prix;
                                    $tva = $totalHT * 0.2;
                                    $totalTTC = $totalHT + $tva;
                                    $totalTTCGlobal += $totalTTC;
                                @endphp
                                <tr>
                                    <td>{{ $article->nom_article }}</td>
                                    <td>{{ $article->quantite }}</td>
                                    <td>{{ $article->prix }} Ar</td>
                                    <td>20 %</td>
                                    <td>{{ number_format($totalHT, 2, '.', ',') }} Ar</td>
                                    <td>{{ number_format($totalTTC, 2, '.', ',') }} Ar</td>

                                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="color: red; font-weight: bold; margin-top: 20px;">
            Total TTC Global : {{ number_format($totalTTCGlobal, 2, '.', ',') }}  Ar
        </p>

        <div class="footer">
            <p><strong>Livraison :</strong></p>
            <p><strong>Date de Livraison :</strong>{{ \Carbon\Carbon::parse($date_livraison)->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
            <p><strong>Lieu de Livraison :</strong> {{ $lieu_livraison }}</p>
            <p><strong>Condition de Paiement :</strong> {{ $condition_paiement }}</p>
        </div>

        <!-- Section signature améliorée -->
        <table class="signature-section">
            <tr>
                <td>
                    La Direction Financière<br>
                    ______________________
                </td>
                <td>
                    La Direction Générale<br>
                    ______________________
                </td>
            </tr>
        </table>
    </div>

</body>

</html>