# Zoho & Zapier Setup — Quick Reference

> Forms → `FormMailer` (admin notification email). Newsletter → Zoho Campaigns. Zapier → webhooks.
> Admin: `/admin` → **Site Settings**. Koi code change nahi chahiye.

---

## 🔐 Zoho Mail (form emails)

**Admin → Site Settings → Zoho Mail**

Setup:
1. **Zoho Mail → Security → App Passwords** → generate for "Mail / Laravel" (normal password se 535 error aata hai)
2. Fill admin fields:
   - **Enable Zoho SMTP:** ON
   - **Username:** full mailbox, e.g. `admissions@yourdomain.com`
   - **Password:** app-specific password
   - **SMTP host:** `smtp.zoho.com` (EU: `smtp.zoho.eu`, India: `smtp.zoho.in`, paid/Workplace: `smtppro.zoho.com`)
   - **Port / Encryption:** `587` + TLS  (ya `465` + SSL — dono match hone chahiye)
   - **Default recipient:** admin inbox, e.g. `admissions@yourdomain.com`
3. Save → Send real test from Contact form → check inbox + `storage/logs/laravel.log`

---

## 📧 Zoho Campaigns (footer newsletter)

**Admin → Site Settings → Zoho Campaigns**

Zoho side:
1. Create mailing list → **Setup** → copy **List Key** + **enable sign-up form** (double opt-in confirmation)
2. Create **Autoresponder** for welcome email
3. [api-console.zoho.com](https://api-console.zoho.com) → Server-based app → scope `ZohoCampaigns.contact.CREATE`, `access_type=offline` → copy Client ID, Secret, Refresh Token

Admin fields:
- **Enable sync:** ON
- **Region:** match your Zoho account (`com` / `eu` / `in` / `com.au` / `jp`)
- **Client ID / Secret / Refresh token** (secrets: blank save = keep old)
- **Mailing list key** (from list Setup)
- **Source label:** optional, e.g. `Website Footer`

Verify: **Test connection** button on admin page.

---

## ⚡ Zapier Webhooks

**Admin → Site Settings → Zapier Webhooks**

Setup:
1. Zapier: **Create Zap → Webhooks by Zapier → Catch Hook** → copy webhook URL
2. Admin: **Create** → Event select → paste URL → **Enabled ON** → Save
3. Press **Send test** → check payload in Zapier → complete rest of Zap (Sheets/CRM/Slack)

Events:
| Event | Trigger |
|---|---|
| `contact.submitted` | Contact form |
| `program.enquiry_submitted` | Programme enquiry |
| `newsletter.subscribed` | Newsletter signup |

- Same event pe multiple Zaps allowed. Disable = toggle OFF (row delete nahi karna)
- Zapier fail ho to user ko success — logs me dekho

---

## ✅ Verify (2 min)

1. Contact form + Programme enquiry + Newsletter — real submit karo
2. Zoho sent folder + admin inbox check karo
3. Zapier admin table me **Last status** = `success`
4. Problem ho to `storage/logs/laravel.log` → `FormMailer failed:` / `Zoho Campaigns` / webhook lines
