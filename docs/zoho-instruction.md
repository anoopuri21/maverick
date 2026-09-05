# Zoho Mail — how this site sends form emails

Sab public forms (contact, programme enquiry, footer newsletter) ek hi jagah se email bhejti hain: **Zoho SMTP**, configured in the admin panel. Form code Zoho ke baare mein kuch nahi jaanta — wo sirf `FormMailer::send()` call karti hai.

## 1. Where to fill credentials

Admin login ke baad:

**Admin → Site Settings → Zoho Mail**

Direct URL: `/admin/zoho-settings`

### Fields (kya likhna hai)

| Field | Meaning | Zoho se kahan milta hai |
|---|---|---|
| **Enable Zoho SMTP** | ON = real Zoho send. OFF = site still works; email Laravel default mailer (usually `log`) use karti hai. | — |
| **Default recipient** | Admin inbox jahan new form emails jaati hain (agar form apna `to` na de). | Aapka admissions / info mailbox |
| **From name** | Email pe naam, e.g. Maverick Business Academy | — |
| **Reply-To** | Optional. Empty ho to visitor ka email reply-to ban jata hai (jab form mein email ho). | Usually same as username, or leave empty |
| **SMTP host** | Zoho SMTP server | See below |
| **Port** | `587` (TLS) recommended, ya `465` (SSL) | Zoho Mail SMTP docs |
| **Encryption** | Port 587 = TLS, port 465 = SSL | Must match port |
| **Zoho mail domain** | Optional reminder only (e.g. `mbalondon.org.uk`) | Your Zoho org domain |
| **Zoho username** | Full mailbox, e.g. `admissions@yourdomain.com` | Zoho Mail account email |
| **App-specific password** | SMTP password. Normal Zoho login password **mat** dalo. | Steps below. Leave blank on later saves to keep the current password. |

### Zoho SMTP host (region)

- Global: `smtp.zoho.com`
- Europe: `smtp.zoho.eu`
- India: `smtp.zoho.in`
- Zoho Mail Plus / Workplace (custom domain, paid): often `smtppro.zoho.com`

Check: Zoho Mail → Settings → Mail Accounts → your account → SMTP.

### App-specific password (important)

1. Zoho Mail mein login karo (same mailbox jo username hai).
2. **Account / Security → App Passwords** (sometimes under [https://accounts.zoho.com](https://accounts.zoho.com) → Security).
3. Generate a password for **Mail** / “Laravel website”.
4. Jo password dikhe, usko **App-specific password** field mein paste karo.
5. Save. Enable Zoho SMTP ON karo.

2FA on hone par Zoho usually app password maangta hai. Normal password se SMTP `535` error aata hai.

## 1b. Email template (logo, header, footer — admin managed)

**Admin → Site Settings → Email Template** (`/admin/manage-mail-template-settings`)

Form emails ka design yahan se control hota hai — code me Laravel ka default template istemaal nahi hota:

| Admin field | Kya karta hai | Empty ho to |
|---|---|---|
| **Header content** (rich text) | Email me form details ke upar dikhta hai (intro line etc.) | Section email me aata hi nahi |
| **Footer content** (rich text) | Form details ke neeche — regards/signature. Default: `Regards, Maverick Business Academy` | Footer email me aata hi nahi (koi automatic "Thanks" fallback nahi) |

- **Logo** email ke top par navy band me apne aap aata hai — source: **General Settings → Logo (White)**. Badalna ho to wahan change karo.
- Har email ka structure: logo band → header rich text → form details table → footer rich text.
- **Send test email** button se default recipient par preview bhej sakte ho.

## 2. Forms currently integrated

| Page / route | What is emailed |
|---|---|
| Contact — `POST /contact` (`contact.submit`) | Name, Email, Phone (if filled), Subject, Message. Honeypot `website` is never emailed. Optional Zapier webhook via admin **Zapier Webhooks** (see [zapier-instruction.md](zapier-instruction.md)). |
| Programme enquiry — `POST /programs/enquire` (`programs.enquire`) | Programme, Name, Email, Phone, Country, Study mode, Qualification, Message (empty fields skipped). |
| Footer newsletter — `POST /newsletter` (`newsletter.subscribe`) | Subscriber email is added to the configured **Zoho Campaigns** mailing list (when enabled in admin). Zoho sends the confirmation email (double opt-in) and welcome email from Campaigns. Admin also receives a notification email via Zoho Mail / `FormMailer`. No local subscriber table — Zoho Campaigns is the source of truth. |

Blog/news search forms and Filament admin save forms do **not** send email.

## 3. Zoho Campaigns (footer newsletter)

Footer newsletter uses **Zoho Campaigns API** (not Zoho Mail SMTP) to add contacts to a mailing list. Confirmation and welcome emails are managed inside Zoho Campaigns — not in Laravel code.

Admin: **Admin → Site Settings → Zoho Campaigns** (`/admin/manage-zoho-campaigns-settings`)

### What you need in Zoho

1. **Mailing list** — Zoho Campaigns → Contacts → Manage Lists → open your list → Setup:
   - Copy the **List Key**
   - **Enable the sign-up form** on that list (required for double opt-in confirmation emails)
2. **Confirmation email** — customize in the list Setup / signup form settings inside Zoho Campaigns
3. **Welcome email** — create an **Autoresponder** in Zoho Campaigns that triggers when a contact joins the list (after they confirm)
4. **OAuth app** — [api-console.zoho.com](https://api-console.zoho.com):
   - Create a **Server-based** (or Self Client) application
   - Scope: `ZohoCampaigns.contact.CREATE`
   - Authorization URL must include `access_type=offline` to get a **refresh token**
   - Copy Client ID, Client Secret, and Refresh Token into the admin page

### Admin fields

| Field | Meaning |
|---|---|
| **Enable Zoho Campaigns sync** | ON = footer signups call `listsubscribe`. OFF = admin notification email only. |
| **Zoho data center** | Must match your account region (`com`, `eu`, `in`, `com.au`, `jp`) |
| **API endpoint** | `campaigns` = classic accounts. `marketing_automation` = **new Zoho accounts / new Campaigns UI** (`/newui/` in the URL). New accounts must use Marketing Automation with a `ZohoMarketingAutomation.contact.CREATE` token — the classic endpoint returns an HTTP 401 HTML page for them. |
| **Mailing list key** | From list Setup in Zoho Campaigns |
| **Contact source label** | e.g. `Website Footer` — shown in Campaigns |
| **Client ID / secret / refresh token** | From Zoho API Console. Secrets: leave blank on save to keep existing values. |

Use **Test connection** on the admin page — it performs a real `getmailinglists` API call and reports whether the saved list key exists in the account (not just a token refresh).

### Flow

1. User submits email in footer
2. Laravel calls Zoho `listsubscribe` API
3. Zoho sends confirmation email to the user (if sign-up form is enabled on the list)
4. User clicks confirm link in email
5. Zoho adds contact to list and can send welcome autoresponder
6. Admin still gets `New newsletter signup` notification via Zoho Mail (`FormMailer`)

If Campaigns sync fails, the user still sees success and admin notification still sends — check `storage/logs/laravel.log` for `Zoho Campaigns` messages.

## 4. How to add a NEW form later

Form ko Zoho ke baare mein kuch nahi pata hona chahiye.

1. Blade form banao (`@csrf`, `method="POST"`, `action` to a named route). UI jaise chaho.
2. Route + controller + FormRequest (existing validation style). Required fields wahi rakho jo aaj required hain.
3. Validated data ko **labels => values** array mein collect karo.
4. Ek line se bhejo:

```php
use App\Services\FormMailer;

app(FormMailer::class)->send([
    'Name' => $validated['name'] ?? '',
    'Email' => $validated['email'] ?? '',
    'Phone' => $validated['phone'] ?? '',
    // koi bhi extra field add karo — mailer names hardcode nahi karta
], 'New something form', [
    'reply_to' => $validated['email'] ?? null,
    // 'to' => 'optional-override@example.com',
]);
```

Empty values email se skip ho jaati hain. Mail fail hone par site 500 nahi karti — log ho jata hai, user ko friendly success / JSON milta hai.

Honeypot / `_token` fields automatically skip.

## 5. Enable / disable / change recipient / test

- **Disable Zoho:** Admin → Zoho Mail → Enable Zoho SMTP OFF. Forms still submit. Email default Laravel mailer (`MAIL_MAILER` in `.env`, usually `log`) pe jaati hai.
- **Change recipient:** Default recipient field badlo, Save. Contact/newsletter/enquiry sab yahi use karte hain unless a form `to` option pass kare. Empty recipient = Site Settings email, then `admissions@mbalondon.org.uk`.
- **Test:**
  1. Credentials save + Enable ON.
  2. Contact page se ek real message bhejo, ya footer newsletter.
  3. Zoho Sent folder + admin inbox check karo.
  4. Fail ho to `storage/logs/laravel.log` mein `FormMailer failed:` dekho.

## 6. Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| `535 Authentication Failed` | Normal password used, wrong mailbox, or app password revoked | New **App Password** banao; username full email ho |
| Newsletter sync log me HTTP 401 **HTML page** | Naya Zoho account (new Campaigns UI) — classic `campaigns.zoho.com` API us org ko pehchanata nahi | Admin → Zoho Campaigns → **API endpoint = Marketing Automation** + scope `ZohoMarketingAutomation.contact.CREATE` se fresh token banao |
| Test connection: `INVALID_OAUTHSCOPE` | Token ka scope selected endpoint se match nahi karta | Classic: `ZohoCampaigns.contact.CREATE`, Marketing Automation: `ZohoMarketingAutomation.contact.CREATE` |
| Connection timeout | Wrong host/port, firewall, shared hosting blocking 587 | Try `smtppro.zoho.com` or region host; 587 TLS vs 465 SSL |
| SSL/TLS error | Encryption ≠ port | 587 + TLS, or 465 + SSL |
| Email goes to spam | From address not the Zoho mailbox / SPF-DKIM | From = Zoho username; domain DNS SPF/DKIM in Zoho |
| Nothing arrives, no 500 | Zoho is OFF, or send failed (logged) | Enable toggle; check `laravel.log` |
| Password “lost” after save | You submitted an empty password | Leave password blank to keep the old one; re-enter app password if needed |

Zoho credentials `.env` mein source of truth **nahi** hain. Sirf admin Zoho Mail settings use karo.
