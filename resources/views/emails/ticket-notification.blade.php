@component('mail::message')
# 🎉 Votre Billet - {{ $event->title }}

Bonjour **{{ $user->name }}**,

@if($participant->event->isFree())
Votre inscription à l'événement **{{ $event->title }}** a été confirmée avec succès !
@else
Merci pour votre paiement ! Votre inscription à l'événement **{{ $event->title }}** est confirmée.
@endif

## 📋 Détails de l'Événement

- **📅 Date:** {{ \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
- **🕐 Horaire:** {{ \Carbon\Carbon::parse($event->date_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->date_end)->format('H:i') }}
- **📍 Lieu:** {{ $event->location }}
@if($event->maps_link)
- **🗺️ Plan:** [Voir sur Maps]({{ $event->maps_link }})
@endif

---

## 🎫 Votre Billet

**Numéro de Billet:** `{{ $ticketNumber }}`

@if(!$participant->event->isFree())
**Facture:** `{{ $participant->invoice_number }}`  
**Montant Payé:** {{ number_format($participant->amount_paid, 3) }} {{ config('payments.currency') }}
@endif

@component('mail::button', ['url' => $ticketUrl, 'color' => 'success'])
📄 Voir Mon Billet
@endcomponent

---

## ✅ Que Faire Maintenant ?

1. **Conservez ce billet** - Vous en aurez besoin pour entrer à l'événement
2. **Présentez le QR Code** - À l'entrée, montrez votre code QR pour validation
3. **Arrivez à l'heure** - Nous vous recommandons d'arriver 10 minutes avant
@if($event->required_materials)
4. **Apportez le matériel** - {{ $event->required_materials }}
@endif

---

## ℹ️ Informations Importantes

@if($event->access_instructions)
**Instructions d'Accès:**  
{{ $event->access_instructions }}
@endif

@if($event->parking_available)
✅ Parking disponible
@endif

@if($event->wifi_available)
✅ WiFi disponible
@endif

@if($event->accessible_pmr)
✅ Accessible PMR
@endif

---

## 📞 Besoin d'Aide ?

- **Organisateur:** {{ optional($event->user)->name ?? 'Organisateur' }}
@if(optional($event->user)->email)
- **Email:** {{ $event->user->email }}
@endif

@if($participant->event->isFree())
**Annulation:** Vous pouvez annuler votre participation depuis la page de l'événement.
@else
**Remboursement:** Consultez notre politique de remboursement sur la page de l'événement.
@endif

---

Nous avons hâte de vous voir à l'événement !

Cordialement,<br>
L'équipe {{ config('app.name') }}

<small style="color: #999;">
Billet généré automatiquement. Ne pas répondre à cet email.
</small>
@endcomponent
