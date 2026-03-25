<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis {{ $devis->reference_projet ?: $devis->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2563eb;
            font-size: 24px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .devis-info {
            background-color: #f9fafb;
            padding: 15px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
        }
        .devis-info p {
            margin: 5px 0;
        }
        .custom-message {
            background-color: #fef3c7;
            padding: 15px;
            border-left: 4px solid #f59e0b;
            margin: 20px 0;
            font-style: italic;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Devis {{ $devis->reference_projet ?: $devis->id }}</h1>
    </div>

    <div class="content">
        @if(isset($senderEmail) && isset($senderName))
            <div style="margin-bottom: 20px; padding: 10px; background-color: #f3f4f6; border-left: 4px solid #3b82f6; font-size: 14px;">
                <strong>Envoyé par :</strong> {{ $senderName }} (<a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a>)
            </div>
        @endif

        <p>Bonjour @if($devis->client_contact) {{ $devis->client_contact }}@endif,</p>

        @if($customMessage)
            <div class="custom-message">
                {!! nl2br(e($customMessage)) !!}
            </div>
        @else
            <p>Veuillez trouver ci-joint notre devis pour votre projet.</p>
        @endif

        <div class="devis-info">
            <p><strong>Référence du devis :</strong> {{ $devis->reference_projet ?: $devis->id }}</p>
            <p><strong>Date d'émission :</strong> @if($devis->date_emission){{ \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') }}@else N/A @endif</p>
            @if($devis->lieu_intervention)
                <p><strong>Lieu d'intervention :</strong> {{ $devis->lieu_intervention }}</p>
            @endif
            @if($devis->duree_validite)
                <p><strong>Validité :</strong> {{ $devis->duree_validite }} jours</p>
            @endif
            <p><strong>Client :</strong> {{ $devis->client_nom }}</p>
        </div>

        <p>Le devis complet est disponible en pièce jointe au format PDF.</p>

        <p>N'hésitez pas à nous contacter si vous avez des questions ou besoin d'informations complémentaires.</p>

        <p>Cordialement,<br>
        <strong>{{ config('app.name', 'Atlantis Montaza') }}</strong></p>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.</p>
        <p>{{ config('app.name', 'Atlantis Montaza') }} - {{ date('Y') }}</p>
    </div>
</body>
</html>
