@component('mail::message')
# 🎉 Félicitations {{ $user->name }} !

Nous sommes ravis de vous informer que votre **certificat de participation** pour l'événement **{{ $event->title }}** est maintenant disponible !

## 📋 Détails de l'Événement

- **Type:** {{ $event->type == 'workshop' ? '🛠️ Workshop' : ($event->type == 'collection' ? '🌱 Collection' : ($event->type == 'training' ? '📚 Formation' : '☕ Repair Café')) }}
- **Date:** {{ \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
- **Lieu:** {{ $event->location }}

Votre participation active à cet événement démontre votre engagement envers l'économie circulaire et le développement durable. Ce certificat atteste de votre contribution à la préservation de l'environnement.

@component('mail::button', ['url' => $certificateUrl, 'color' => 'success'])
📄 Voir Mon Certificat
@endcomponent

---

### 💡 Comment utiliser votre certificat ?

- **Télécharger:** Cliquez sur le bouton ci-dessus pour voir et télécharger votre certificat
- **Imprimer:** Vous pouvez imprimer votre certificat directement depuis votre navigateur
- **Partager:** Partagez votre réussite sur les réseaux sociaux avec #Waste2Product #ÉconomieCirculaire

---

### 🌱 Continuez votre engagement

Explorez nos prochains événements et continuez à contribuer à un avenir plus durable :

@component('mail::button', ['url' => route('Events.index')])
Découvrir les Événements
@endcomponent

---

**Numéro de Certificat:** {{ \App\Helpers\CertificateHelper::generateCertificateNumber($participant) }}

Merci pour votre participation et votre engagement !

Cordialement,<br>
L'équipe {{ config('app.name') }}

---

<small style="color: #999;">
Ce certificat est délivré automatiquement par la plateforme Waste2Product. Pour toute question, veuillez contacter l'organisateur de l'événement.
</small>
@endcomponent
