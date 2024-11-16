<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Demande de Devis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .container {
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 80px;
        }

        .company-name {
            font-size: 30px;
            color: green;
            text-align: center;
            flex-grow: 1;
            margin-top: -55px;
            font-family : 'Times New Roman', Times, serif;
        }

        .company-info-left {
            width: 48%;
        }

        .company-info-left strong {
            font-weight: bold;
            line-height: 1.8; 
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
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }

        th {
            background-color: rgb(221, 221, 221);
        }

        .footer {
            font-size: 14px;
            margin-top: 20px;
        }

        ul {
            list-style-type: disc;
            margin-left: 20px;
        }

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
            opacity: 0.2;
            z-index: -1;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header avec logo et info entreprise -->
        <div class="header">
            <img src="{{ public_path('images/hita_logo.jpg') }}" alt="Logo HITA">
            <div class="company-name">Huillerie Industrielle de Tamatave</div>
        </div>

        <div class="company-info-left">
            <strong>NIF :</strong> 3000063634<br>
            <strong>STAT :</strong> 463013120000000132<br>
            <strong>RCS :</strong> 2000B00066<br>
            <strong>Tél :</strong> 53 338 55 - 53 338 57<br>
            <strong>Adresse :</strong> Andoranga, Port Fluvial<br>
            <strong>Ville :</strong> TOAMASINA 501
        </div>

        <!-- Titre du document -->
        <div class="title">DEMANDE DE DEVIS</div>
        <p><strong>Date :</strong> <span>{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}</span></p>

        <p><strong>Référence :</strong> <span>{{ $reference }}</span></p>

        <!-- Tableau des articles -->
        <table>
            <thead>
                <tr>
                    <th>Articles</th>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articles as $article)
                    <tr>
                        <td>{{ $article->nom_article }}</td>
                        <td>{{ $article->description }}</td>
                        <td>{{ $article->quantite }}</td>
                        <td>{{ $article->unite }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer avec demande d'information -->
        <div class="footer">
            <p><strong>Merci de bien vouloir inclure dans votre devis les informations suivantes :</strong></p>
            <ul>
                <li>Le prix unitaire de chaque article</li>
                <li>Les frais de livraison (le cas échéant)</li>
                <li>Les délais de livraison</li>
                <li>Les conditions de paiement</li>
            </ul>
            <p>Nous vous prions de bien vouloir nous faire parvenir votre devis par retour d'email à l'adresse suivante : <strong>{{ $email }}</strong> avant le <strong>{{ \Carbon\Carbon::parse($date_fin)->locale('fr')->isoFormat('D MMMM YYYY') }}</strong>.</p>
            <p>Nous restons à votre disposition pour toute information complémentaire.</p>
            <p>Dans l'attente de votre réponse, nous vous remercions pour votre collaboration.</p>

            <p>Cordialement,</p>

            <p>{{ $acheteur['nom'] }}<br>
                {{ $acheteur['poste'] }}<br>
                {{ $acheteur['entreprise'] }}<br>
                {{ $acheteur['telephone'] }}<br>
                {{ $acheteur['email'] }}</p>
        </div>
    </div>

</body>
</html>
