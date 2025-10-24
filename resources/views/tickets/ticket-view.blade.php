<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet - {{ $event->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
            padding: 40px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .ticket-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
            opacity: 0.1;
        }
        
        .ticket-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .ticket-subtitle {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .ticket-body {
            padding: 40px;
        }
        
        .ticket-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #06D6A0;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }
        
        .qr-section {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            margin: 30px 0;
        }
        
        .qr-code {
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .ticket-number {
            font-size: 24px;
            font-weight: 700;
            color: #2E7D47;
            margin-top: 20px;
            font-family: 'Courier New', monospace;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-attended {
            background: #c3e6cb;
            color: #155724;
        }
        
        .instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .instructions h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .instructions ul {
            list-style: none;
            padding: 0;
        }
        
        .instructions li {
            padding: 8px 0;
            color: #856404;
        }
        
        .instructions li::before {
            content: '✓ ';
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2E7D47, #06D6A0);
            color: white;
            box-shadow: 0 4px 12px rgba(46, 125, 71, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(46, 125, 71, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #2E7D47;
            border: 2px solid #2E7D47;
        }
        
        .btn-secondary:hover {
            background: #2E7D47;
            color: white;
        }
        
        .ticket-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .ticket-container {
                box-shadow: none;
            }
            
            .action-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <div class="ticket-title">🎫 BILLET D'ENTRÉE</div>
            <div class="ticket-subtitle">{{ $event->title }}</div>
        </div>
        
        <div class="ticket-body">
            <!-- Participant Info -->
            <div class="ticket-info">
                <div class="info-item">
                    <div class="info-label">Participant</div>
                    <div class="info-value">{{ $participant->user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $participant->user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date & Heure</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('D MMM YYYY') }}<br>
                        {{ \Carbon\Carbon::parse($event->date_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->date_end)->format('H:i') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Lieu</div>
                    <div class="info-value">{{ $event->location }}</div>
                </div>
            </div>

            @if(!$event->isFree())
            <div class="ticket-info">
                <div class="info-item">
                    <div class="info-label">Facture</div>
                    <div class="info-value">{{ $participant->invoice_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Montant Payé</div>
                    <div class="info-value">{{ number_format($participant->amount_paid, 3) }} {{ config('payments.currency') }}</div>
                </div>
            </div>
            @endif
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <h3 style="color: #2E7D47; margin-bottom: 20px;">Présentez ce QR Code à l'entrée</h3>
                <div class="qr-code">
                    {!! \App\Helpers\TicketHelper::generateQRCodeSVG($participant) !!}
                </div>
                <div class="ticket-number">
                    {{ \App\Helpers\TicketHelper::generateTicketNumber($participant) }}
                </div>
                <div class="status-badge {{ $participant->attendance_status === 'attended' ? 'status-attended' : 'status-confirmed' }}">
                    @if($participant->attendance_status === 'attended')
                        ✓ Présent
                    @else
                        ✓ Confirmé
                    @endif
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <h3>📝 Instructions Importantes</h3>
                <ul>
                    <li>Arrivez 10 minutes avant le début de l'événement</li>
                    <li>Présentez ce billet (QR Code) à l'entrée pour validation</li>
                    <li>Conservez ce billet pendant toute la durée de l'événement</li>
                    @if($event->required_materials)
                    <li>Matériel requis: {{ $event->required_materials }}</li>
                    @endif
                </ul>
            </div>

            @if($event->access_instructions)
            <div style="background: #e7f3ff; border-left: 4px solid #0066cc; padding: 15px; border-radius: 10px; margin: 20px 0;">
                <strong style="color: #0066cc;">ℹ️ Instructions d'Accès:</strong><br>
                <span style="color: #004080;">{{ $event->access_instructions }}</span>
            </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-primary">
                    🖨️ Imprimer le Billet
                </button>
                <button onclick="downloadTicket()" class="btn btn-secondary">
                    📥 Télécharger
                </button>
                <a href="{{ route('Events.show', $event->id) }}" class="btn btn-secondary">
                    ← Retour à l'Événement
                </a>
            </div>
        </div>
        
        <div class="ticket-footer">
            <p>Organisé par {{ optional($event->user)->name ?? 'Organisateur' }}</p>
            <p style="margin-top: 5px;">
                Billet généré le {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY à HH:mm') }}
            </p>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadTicket() {
            const ticket = document.querySelector('.ticket-container');
            const buttons = document.querySelector('.action-buttons');
            
            buttons.style.display = 'none';
            
            html2canvas(ticket, {
                scale: 2,
                backgroundColor: '#ffffff',
                logging: false
            }).then(canvas => {
                buttons.style.display = 'flex';
                
                canvas.toBlob(blob => {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'Billet_{{ str_replace(" ", "_", $event->title) }}_{{ str_replace(" ", "_", $participant->user->name) }}.png';
                    link.click();
                    URL.revokeObjectURL(url);
                });
            }).catch(error => {
                buttons.style.display = 'flex';
                alert('Erreur lors du téléchargement. Veuillez utiliser le bouton Imprimer.');
            });
        }
    </script>
</body>
</html>
