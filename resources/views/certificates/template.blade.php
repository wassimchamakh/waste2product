<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Participation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .certificate-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 60px;
            border: 20px solid #2E7D47;
            border-image: linear-gradient(45deg, #2E7D47, #06D6A0) 1;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .certificate-container::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 2px solid #06D6A0;
            pointer-events: none;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #2E7D47, #06D6A0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
        }
        
        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: #2E7D47;
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 2px;
        }
        
        .header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #06D6A0;
            font-weight: 400;
            letter-spacing: 1px;
        }
        
        .divider {
            width: 200px;
            height: 3px;
            background: linear-gradient(to right, #2E7D47, #06D6A0, #2E7D47);
            margin: 30px auto;
        }
        
        .content {
            text-align: center;
            margin: 40px 0;
            position: relative;
            z-index: 1;
        }
        
        .content p {
            font-size: 18px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .participant-name {
            font-family: 'Great Vibes', cursive;
            font-size: 64px;
            color: #2E7D47;
            margin: 30px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .event-details {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 30px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 5px solid #06D6A0;
        }
        
        .event-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #2E7D47;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .event-info {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .event-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 16px;
        }
        
        .event-info-item i {
            color: #06D6A0;
            font-size: 20px;
        }
        
        .achievement-text {
            font-size: 16px;
            color: #666;
            line-height: 1.8;
            margin: 20px auto;
            max-width: 800px;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
            position: relative;
            z-index: 1;
        }
        
        .signature-block {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            width: 200px;
            height: 2px;
            background: #2E7D47;
            margin: 20px auto 10px;
        }
        
        .signature-block h4 {
            font-family: 'Playfair Display', serif;
            color: #2E7D47;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .signature-block p {
            color: #999;
            font-size: 14px;
        }
        
        .certificate-number {
            position: absolute;
            bottom: 20px;
            right: 40px;
            font-size: 12px;
            color: #999;
            font-family: 'Courier New', monospace;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(6, 214, 160, 0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
        
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .btn-download {
            background: linear-gradient(135deg, #2E7D47, #06D6A0);
            color: white;
        }
        
        .btn-download:hover {
            box-shadow: 0 4px 12px rgba(46, 125, 71, 0.3);
            transform: translateY(-2px);
        }
        
        .btn-print {
            background: white;
            color: #2E7D47;
            border: 2px solid #2E7D47;
        }
        
        .btn-print:hover {
            background: #2E7D47;
            color: white;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .certificate-container {
                box-shadow: none;
                page-break-after: always;
            }
            
            .action-buttons {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i>
            Imprimer
        </button>
        <button onclick="downloadCertificate()" class="btn btn-download">
            <i class="fas fa-download"></i>
            Télécharger
        </button>
    </div>
    <div class="certificate-container">
        <div class="watermark">WASTE2PRODUCT</div>
        
        <div class="header">
            <div class="logo">W2P</div>
            <h1>CERTIFICAT</h1>
            <h2>de Participation</h2>
        </div>
        
        <div class="divider"></div>
        
        <div class="content">
            <p style="font-size: 16px; color: #999; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px;">
                Il est certifié que
            </p>
            
            <div class="participant-name">
                {{ $user->name }}
            </div>
            
            <p style="font-size: 18px; color: #666; margin-top: 20px;">
                a participé avec succès à l'événement
            </p>
            
            <div class="event-details">
                <div class="event-title">{{ $event->title }}</div>
                
                <div class="event-info">
                    <div class="event-info-item">
                        <i class="fas fa-tag"></i>
                        <span><strong>Type:</strong> 
                            @if($event->type == 'workshop')
                                🛠️ Workshop
                            @elseif($event->type == 'collection')
                                🌱 Collection
                            @elseif($event->type == 'training')
                                📚 Formation
                            @elseif($event->type == 'repair_cafe')
                                ☕ Repair Café
                            @endif
                        </span>
                    </div>
                    <div class="event-info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><strong>Date:</strong> {{ $eventDate }}</span>
                    </div>
                    <div class="event-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong>Lieu:</strong> {{ $event->location }}</span>
                    </div>
                </div>
            </div>
            
            <div class="achievement-text">
                Ce certificat atteste de la participation active à cet événement dans le cadre de l'économie circulaire 
                et du développement durable. Le participant a démontré son engagement envers la préservation de 
                l'environnement et la valorisation des déchets.
            </div>
            
            <div style="text-align: center; margin: 30px 0; padding: 20px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 10px;">
                <p style="font-size: 16px; color: #666; margin-bottom: 10px;">
                    <strong>Organisé par:</strong>
                </p>
                <p style="font-family: 'Playfair Display', serif; font-size: 24px; color: #2E7D47; font-weight: 600; margin: 0;">
                    {{ optional($event->user)->name ?? 'Organisateur' }}
                </p>
                @if(optional($event->user)->email)
                <p style="font-size: 14px; color: #999; margin-top: 5px;">
                    <i class="fas fa-envelope"></i> {{ $event->user->email }}
                </p>
                @endif
            </div>
        </div>
        
        <div class="footer">
            <div class="signature-block">
                <div class="signature-line"></div>
                <h4>{{ optional($event->user)->name ?? 'Organisateur' }}</h4>
                <p>Organisateur de l'événement</p>
            </div>
            
            <div class="signature-block">
                <div class="signature-line"></div>
                <h4>Waste2Product</h4>
                <p>Plateforme d'Économie Circulaire</p>
            </div>
        </div>
        
        <div class="certificate-number">
            N° {{ \App\Helpers\CertificateHelper::generateCertificateNumber($participant) }}<br>
            Délivré le {{ $issueDate }}
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadCertificate() {
            const certificate = document.querySelector('.certificate-container');
            const buttons = document.querySelector('.action-buttons');
            
            // Hide buttons before capture
            buttons.style.display = 'none';
            
            // Use html2canvas to capture the certificate
            html2canvas(certificate, {
                scale: 2,
                backgroundColor: '#ffffff',
                logging: false,
                useCORS: true
            }).then(canvas => {
                // Show buttons again
                buttons.style.display = 'flex';
                
                // Convert to blob and download
                canvas.toBlob(blob => {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'Certificat_{{ str_replace(" ", "_", $user->name) }}_{{ str_replace(" ", "_", $event->title) }}.png';
                    link.click();
                    URL.revokeObjectURL(url);
                });
            }).catch(error => {
                buttons.style.display = 'flex';
                console.error('Error generating certificate:', error);
                alert('Erreur lors du téléchargement. Veuillez utiliser le bouton Imprimer ou faire une capture d\'écran.');
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P or Cmd+P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // Ctrl+S or Cmd+S for download
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                downloadCertificate();
            }
        });
    </script>
</body>
</html>
