<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
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
            background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .event-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .event-info h2 {
            margin-top: 0;
            color: #2E7D47;
            font-size: 18px;
        }
        .event-detail {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        .event-detail i {
            margin-right: 10px;
            color: #2E7D47;
        }
        .message-content {
            background: #fff;
            padding: 20px;
            border-left: 4px solid #2E7D47;
            margin: 20px 0;
            white-space: pre-wrap;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e0e0e0;
            border-top: none;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #2E7D47;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background: #25633a;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌱 Waste2Product</h1>
        <p>Notification pour votre événement</p>
    </div>

    <div class="content">
        <p>Bonjour <strong>{{ $participant->name }}</strong>,</p>

        <div class="event-info">
            <h2>📅 {{ $event->title }}</h2>
            <div class="event-detail">
                <span>📍 <strong>Lieu:</strong> {{ $event->location }}</span>
            </div>
            <div class="event-detail">
                <span>📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date_start)->format('d/m/Y à H:i') }}</span>
            </div>
        </div>

        <div class="message-content">
            {{ $messageContent }}
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/events/' . $event->id) }}" class="button">
                Voir l'événement
            </a>
        </div>

        <p style="margin-top: 30px; color: #666;">
            Cordialement,<br>
            <strong>L'équipe Waste2Product</strong>
        </p>
    </div>

    <div class="footer">
        <p>
            Vous recevez cet email car vous êtes inscrit à l'événement "{{ $event->title }}".<br>
            Si vous avez des questions, contactez l'organisateur.
        </p>
        <p style="margin-top: 15px; font-size: 12px; color: #999;">
            © {{ date('Y') }} Waste2Product. Tous droits réservés.
        </p>
    </div>
</body>
</html>
