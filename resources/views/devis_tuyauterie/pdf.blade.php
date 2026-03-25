<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis {{ $devis->reference_projet ?? $devis->id }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            margin-bottom: 60px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .title {
            margin-bottom: 10px;
        }

        .details {
            margin-bottom: 20px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table td {
            padding: 5px;
        }

        .details table td:first-child {
            width: 20%;
        }

        .main-content {
            margin-bottom: 20px;
        }

        .main-content table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
            border: 1px solid #f0f0f0;
        }

        .main-content table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .main-content table th,
        .main-content table td {
            border-left: 1px solid #f0f0f0;
            border-right: 1px solid #f0f0f0;
            text-align: right;
            padding-right: 10px;
            padding-bottom: 10px;
        }

        .main-content table th {
            background-color: #f0f0f0;
            text-align: center;
        }

        #footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: auto;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #f0f0f0;
            padding-top: 10px;
            background: white;
            padding-bottom: 30px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            text-align: left;
        }

        .company-info {
            display: inline-block;
            vertical-align: top;
            width: 48%;
            box-sizing: border-box;
            font-size: 12px;
            line-height: 1.5;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: left;
            float: right;
        }

        .company_info {
            border: 2px solid #000;
            padding: 5px;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            text-align: left !important;
            padding-left: 10px !important;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .text-xs {
            font-size: 0.60rem;
            line-height: 0.75rem;
            margin: 0;
        }

        .footer {
            border-top: 1px solid #f0f0f0;
            margin: 0 10px;
        }

        .table_recap {
            border-collapse: collapse;
            position: absolute;
            bottom: 200px;
            left: 20px;
            right: 20px;
            width: calc(100% - 40px);
        }

        .table_recap th {
            background-color: #f0f0f0;
            text-align: center;
            padding: 5px;
            font-size: larger;
        }

        .table_recap td {
            padding: 5px;
            vertical-align: top;
        }

        .entreprise_nom {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="footer" class="footer">
        @if(isset($entite))
            {{ strtoupper($entite->name) }} - {{ $entite->adresse }} - {{ $entite->code_postal }} - {{ $entite->ville }} - FRANCE <br>
            Téléphone : {{ $entite->tel }}
        @endif
    </div>
    <div class="container">
        <!-- Header -->
        <div class="">
            @if(isset($entite) && $entite->logo)
                <img src="{{ public_path($entite->logo) }}" alt="Logo" style="width: 30%; margin-bottom: 20px;">
            @else
                <div style="width: 30%; margin-bottom: 20px;">LOGO</div>
            @endif
            <div style="float: right; text-align: left; width: 48%;">
                <h2 style="margin-bottom: 0px;">DEVIS {{ $devis->reference_projet ?? 'N/A' }}</h2>
                <p style="margin-top: 0px;">Le {{ $devis->date_emission ? \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') : date('d/m/Y') }}</p>
            </div>
        </div>
        <div class="header">
            <div class="company-info left">
                @if(isset($entite))
                    <strong>{{ strtoupper($entite->name) }}</strong><br>
                    {{ $entite->adresse }}<br>
                    {{ $entite->code_postal }} - {{ $entite->ville }}<br>
                    FRANCE
                @endif
            </div>
            <div class="company-info right">
                <div class="company_info">
                    <strong class="entreprise_nom">{{ $devis->client_nom }}</strong><br>
                    @if($devis->client_contact) Attn: {{ $devis->client_contact }}<br> @endif
                    {!! nl2br(e($devis->client_adresse)) !!}
                </div>
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            @if($devis->lieu_intervention)
                <strong>Lieu d'intervention :</strong> {{ $devis->lieu_intervention }}<br>
            @endif
            @if($devis->duree_validite)
                <strong>Validité :</strong> {{ $devis->duree_validite }} jours<br>
            @endif
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Qté</th>
                        <th>PU HT</th>
                        <th>Total HT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devis->sections as $section)
                        <tr class="section-title">
                            <td colspan="4">{{ $section->titre }}</td>
                        </tr>
                        @foreach($section->lignes as $ligne)
                            <tr style="page-break-inside: avoid;">
                                <td style="text-align: left; padding-left: 10px;">
                                    {{ $ligne->designation }}
                                </td>
                                <td style="text-align: right;" class="whitespace-nowrap">
                                    {{ number_format($ligne->quantite, 2, ',', ' ') }} {{ $ligne->unite }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} €
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ number_format($ligne->total_ht, 2, ',', ' ') }} €
                                </td>
                            </tr>
                        @endforeach
                        <tr style="background-color: #f9f9f9;">
                            <td colspan="3" style="text-align: right; padding-right: 10px;"><strong>Sous-total {{ $section->titre }}</strong></td>
                            <td style="text-align: right;" class="whitespace-nowrap"><strong>{{ number_format($section->lignes->sum('total_ht'), 2, ',', ' ') }} €</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Récapitulatif -->
        <div>
            <table class="table_recap">
                <thead>
                    <tr>
                        <th style="border-right: 2px solid #f0f0f0;">Conditions</th>
                        <th colspan="3" style="border-left: 2px solid #f0f0f0;">Récapitulatif Financier</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px; border-right: 2px solid #f0f0f0; vertical-align: top;">
                            @if($devis->conditions_paiement)
                                <strong>Conditions de paiement :</strong><br>
                                {{ $devis->conditions_paiement }}<br><br>
                            @endif
                            @if($devis->delais_execution)
                                <strong>Délais d'exécution :</strong><br>
                                {{ $devis->delais_execution }}<br><br>
                            @endif
                            @if($devis->duree_validite)
                                <strong>Validité du devis :</strong><br>
                                {{ $devis->duree_validite }} jours
                            @endif
                        </td>
                        <td style="border-right: 2px solid #f0f0f0; vertical-align: top;">
                            <!-- Espace pour informations complémentaires si nécessaire -->
                        </td>
                        <td style="text-align: left; vertical-align: top;">
                            <p><strong>Montant net HT :</strong></p>
                            @php
                                $totalHT = $devis->sections->flatMap->lignes->sum('total_ht');
                                $tva = $totalHT * 0.20; // TVA 20% par défaut
                                $totalTTC = $totalHT + $tva;
                            @endphp
                            <p><strong>TVA (20%) :</strong></p>
                            <p><strong>Montant Total TTC :</strong></p>
                        </td>
                        <td style="text-align: right; vertical-align: top;">
                            <p><strong>{{ number_format($totalHT, 2, ',', ' ') }} €</strong></p>
                            <p><strong>{{ number_format($tva, 2, ',', ' ') }} €</strong></p>
                            <p><strong>{{ number_format($totalTTC, 2, ',', ' ') }} €</strong></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
