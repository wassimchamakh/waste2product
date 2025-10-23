# 🧪 Ticketing System - Testing Guide

## Quick Test Instructions

### 🆓 **Test 1: Free Event Workflow**

1. **Create a Free Event:**
   - Go to "Créer un Événement"
   - Set price to `0` TND
   - Fill in all details
   - Publish event

2. **Register for Event:**
   - Log in as a participant
   - Go to event page
   - Click "S'inscrire maintenant"
   - ✅ Status should be: `registered`

3. **Confirm Participation:**
   - You should see a blue box: "Confirmer votre participation"
   - Click "Confirmer ma participation" button
   - ✅ Status changes to: `confirmed`
   - ✅ Email sent with ticket

4. **View Ticket:**
   - Purple box appears: "Votre Billet"
   - Click "Voir Mon Billet" button
   - ✅ Ticket opens in new tab
   - ✅ Shows QR code, ticket number, event details

5. **Download/Print Ticket:**
   - Click "Télécharger (PNG)" button
   - ✅ Ticket downloads as PNG image
   - Click "Imprimer" button
   - ✅ Print dialog opens

---

### 💰 **Test 2: Paid Event Workflow**

1. **Create a Paid Event:**
   - Go to "Créer un Événement"
   - Set price to `50` TND
   - Fill in all details
   - Publish event

2. **Register and Pay:**
   - Log in as a participant
   - Go to event page
   - Click "S'inscrire et payer"
   - ✅ Redirected to Stripe payment page

3. **Complete Payment:**
   - Use test card: `4242 4242 4242 4242`
   - Any future expiry date (e.g., 12/25)
   - Any 3-digit CVC
   - Click "Pay"
   - ✅ Redirected to payment success page

4. **Check Status:**
   - Go back to event page
   - ✅ Status: `confirmed`
   - ✅ Payment status: `completed`
   - ✅ Email sent with invoice + ticket

5. **View Ticket:**
   - Purple box shows: "Votre Billet"
   - Click "Voir Mon Billet"
   - ✅ Ticket shows payment information
   - ✅ Invoice number visible
   - ✅ QR code displayed

---

### 📱 **Test 3: QR Code Verification (Organizer)**

1. **Access Verification URL:**
   ```
   GET /evenements/{eventId}/ticket/verify/{ticketNumber}
   ```
   Example:
   ```
   GET /evenements/4/ticket/verify/TKT-2025-0004-0020
   ```

2. **Expected Response:**
   ```json
   {
       "success": true,
       "message": "Participant marqué comme présent",
       "data": {
           "participant_id": 20,
           "participant_name": "John Doe",
           "participant_email": "john@example.com",
           "ticket_number": "TKT-2025-0004-0020",
           "attendance_status": "attended",
           "already_attended": false,
           "registration_date": "15/01/2025 à 14:30"
       }
   }
   ```

3. **Verify Status Change:**
   - Go to event page
   - Open participants modal
   - ✅ Participant status changed to "Présent"

4. **Scan Same QR Again:**
   - Same verification URL
   - ✅ Response: `"already_attended": true`
   - ✅ Message: "Participant déjà enregistré comme présent"

---

### ✉️ **Test 4: Email Verification**

#### **Free Event Email:**
Check inbox for email with subject:
```
Votre Billet - {Event Title}
```

Email should contain:
- ✅ Event date, time, location
- ✅ Google Maps link
- ✅ "Voir Mon Billet" button
- ✅ Instructions (présenter le billet, QR code scan, arriver 15min avant)
- ✅ Access info (parking, WiFi, accessibility) - if available
- ✅ Organizer contact info

#### **Paid Event Email:**
Additional content:
- ✅ Invoice number
- ✅ Amount paid
- ✅ Payment date
- ✅ "Politique d'annulation" link

---

### 🔐 **Test 5: Authorization Tests**

#### **Unauthorized Access:**
1. Try to view another user's ticket:
   ```
   GET /evenements/4/ticket/20
   ```
   ✅ Should show: "403 Non autorisé"

2. Try to verify ticket as non-organizer:
   ```
   GET /evenements/4/ticket/verify/TKT-2025-0004-0020
   ```
   ✅ Should return JSON:
   ```json
   {
       "success": false,
       "message": "Non autorisé"
   }
   ```

#### **Ticket Eligibility:**
1. Register for free event but don't confirm:
   - Try to view ticket
   - ✅ Should redirect with error: "Votre billet n'est pas encore disponible"

2. Register for paid event but don't pay:
   - Try to view ticket
   - ✅ Should redirect with error about payment

---

### 🎫 **Test 6: Ticket Appearance**

#### **Visual Checks:**
- ✅ Purple/violet gradient header
- ✅ Event title clearly visible
- ✅ Ticket number in format: `TKT-YYYY-EVENTID-PARTICIPANTID`
- ✅ Participant name and email
- ✅ Event date in format: `DD/MM/YYYY`
- ✅ Event time in format: `HH:MM`
- ✅ Location with full address
- ✅ QR code: 200x200 pixels, black on white
- ✅ Status badge (blue for confirmed, green for attended)

#### **For Paid Events Only:**
- ✅ "Informations de Paiement" section
- ✅ Amount paid
- ✅ Invoice number
- ✅ Payment date

#### **Instructions Section:**
- ✅ Yellow background
- ✅ Checkmarks for each instruction
- ✅ 3 main instructions visible

#### **Access Section (conditional):**
- ✅ Blue background
- ✅ Icons for parking, WiFi, accessibility
- ✅ Only shows if enabled in event settings

---

### 🖨️ **Test 7: Print Functionality**

1. Click "Imprimer" button
2. Print preview should show:
   - ✅ White background (no gradients)
   - ✅ No shadows
   - ✅ Clean QR code
   - ✅ All essential information
   - ✅ Readable fonts
   - ✅ Action buttons hidden

---

### 💾 **Test 8: Download Functionality**

1. Click "Télécharger (PNG)" button
2. Check download:
   - ✅ Filename format: `Billet_{EventTitle}_{ParticipantName}.png`
   - ✅ Image size: Full ticket visible
   - ✅ QR code readable
   - ✅ All text sharp and clear

---

### 🔄 **Test 9: Status Transitions**

#### **Free Event:**
```
registered → [Confirm] → confirmed → [QR Scan] → attended
```

#### **Paid Event:**
```
registered → [Pay] → confirmed → [QR Scan] → attended
    ↓
pending_payment
```

Test each transition:
1. ✅ Register: Status = `registered`
2. ✅ Confirm/Pay: Status = `confirmed`
3. ✅ QR Scan: Status = `attended`

---

### 🚨 **Test 10: Edge Cases**

#### **Try to confirm twice (free event):**
- Confirm participation
- Refresh page
- Try to confirm again
- ✅ Button should disappear
- ✅ Should show ticket button instead

#### **Invalid ticket number:**
```
GET /evenements/4/ticket/verify/INVALID-123
```
✅ Response:
```json
{
    "success": false,
    "message": "Format de billet invalide"
}
```

#### **Ticket from different event:**
```
GET /evenements/5/ticket/verify/TKT-2025-0004-0020
```
(Ticket belongs to event 4, but verifying on event 5)

✅ Response:
```json
{
    "success": false,
    "message": "Billet non trouvé pour cet événement"
}
```

#### **Cancelled participation:**
- Register for event
- Cancel registration
- Try to view ticket
- ✅ Should show error

---

### 📊 **Test 11: Database Verification**

After each test, verify database:

```sql
-- Check participant status
SELECT id, user_id, event_id, attendance_status, payment_status 
FROM participants 
WHERE event_id = 4;

-- Check if email was sent
-- Look in logs: storage/logs/laravel.log
```

Expected states:

| Test Type | attendance_status | payment_status | email_sent |
|-----------|------------------|----------------|------------|
| Free (registered) | registered | not_required | false |
| Free (confirmed) | confirmed | not_required | true |
| Paid (unpaid) | registered | pending_payment | false |
| Paid (paid) | confirmed | completed | true |
| Attended | attended | - | true |

---

### 🎯 **Test 12: Mobile Responsiveness**

Open ticket on mobile device:
- ✅ Ticket fits screen width
- ✅ QR code visible and scannable
- ✅ Buttons touch-friendly
- ✅ Text readable
- ✅ No horizontal scroll

---

### ✅ **Success Criteria**

All tests should pass with:
- ✅ No PHP errors in logs
- ✅ Correct status transitions
- ✅ Emails sent successfully
- ✅ QR codes generate and verify
- ✅ Authorization checks work
- ✅ Tickets are printable/downloadable
- ✅ UI displays correctly on all devices

---

## 🐛 **If Something Fails:**

### **Email not sending:**
1. Check `.env` mail settings
2. Run: `php artisan config:clear`
3. Check `storage/logs/laravel.log`

### **QR code not showing:**
1. Verify QR package installed: `composer show simplesoftwareio/simple-qrcode`
2. Check PHP extensions: `php -m | grep gd`

### **Ticket not accessible:**
1. Check participant status in database
2. Verify payment status for paid events
3. Check authorization (logged in as correct user?)

### **Download not working:**
1. Check browser console for errors
2. Verify html2canvas CDN is accessible
3. Try print function as alternative

---

## 📝 **Testing Checklist**

Print this checklist and mark each test:

- [ ] Free event registration
- [ ] Free event confirmation
- [ ] Free event ticket email
- [ ] Free event ticket view
- [ ] Paid event registration
- [ ] Paid event payment
- [ ] Paid event ticket email  
- [ ] Paid event ticket view
- [ ] QR code verification
- [ ] Mark as attended
- [ ] Duplicate QR scan
- [ ] Unauthorized access prevention
- [ ] Ticket download (PNG)
- [ ] Ticket print
- [ ] Mobile responsiveness
- [ ] Email content verification
- [ ] All edge cases

---

## 🎉 **Congratulations!**

If all tests pass, your ticketing system is fully operational! 🚀
