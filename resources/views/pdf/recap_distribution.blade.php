<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif de la Distribution</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.5;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Récapitulatif de la Distribution</h1>

        <p><strong>Destinataire :</strong> {{ $distribution->destinataire }}</p>
        <p><strong>Adresse :</strong> {{ $distribution->adresse }}</p>
        <p><strong>Téléphone :</strong> {{ $distribution->telephone }}</p>
        <p><strong>Date de la Distribution :</strong> {{ \Carbon\Carbon::parse($distribution->date_souhaitee)->format('d/m/Y') }}</p>
        <p><strong>Bénévole :</strong> {{ $distribution->benevole->user->lastname }} {{ $distribution->benevole->user->firstname }}</p>

        <h2>Aliments Distribués</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($distribution->stocks as $stock)
                    <tr>
                        <td>{{ $stock->produit->nom }}</td>
                        <td>{{ $stock->pivot->quantite }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
