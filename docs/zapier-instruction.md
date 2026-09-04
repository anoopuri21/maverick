# Zapier Webhooks — admin-managed integration

Yeh site form events ko **Zapier** se connect karti hai via **webhook URLs**. Zap banana aur automation design karna **client / Zapier account** ka kaam hai — hum sirf webhook URL admin panel me store karte hain aur event fire hone par data POST karte hain.

## 1. Kahan configure karein

Admin login ke baad:

**Admin → Site Settings → Zapier Webhooks**

Direct URL: `/admin/zapier-webhooks`

Har row = ek Zapier webhook URL + ek event (Contact / Newsletter / Programme enquiry). Ek hi event ke liye **multiple rows** ho sakti hain (e.g. CRM + Slack dono).

Purana `.env` variable `ZAPIER_CONTACT_WEBHOOK_URL` ab use nahi hota. Agar pehle se URL `.env` me thi, usko admin me ek nayi row bana ke paste karo (one-time).

## 2. Naya Zap connect kaise karein (client / admin)

Zapier side (client karega):

1. [zapier.com](https://zapier.com) → **Create Zap**
2. Trigger: **Webhooks by Zapier** → **Catch Hook**
3. Continue — Zapier ek unique **Webhook URL** dega (e.g. `https://hooks.zapier.com/hooks/catch/...`)
4. URL copy karo

Site side (aap admin me):

1. **Admin → Zapier Webhooks → Create**
2. **Event** select karo (neeche list)
3. **Webhook URL** paste karo
4. Optional **Label** (e.g. "HubSpot leads")
5. **Enabled** ON → Save
6. Table me **Send test** dabao — Zapier me test payload dikhna chahiye
7. Zapier me baaki steps complete karo (Google Sheets, CRM, Slack, etc.)

Code deploy ya developer ki zaroorat **nahi** jab tak aap existing events use kar rahe ho.

## 3. Available events & fields

Har POST me automatically ye meta fields bhi jaati hain:

| Field | Description |
|---|---|
| `_event` | Event key (e.g. `contact.submitted`) |
| `_triggered_at` | ISO 8601 timestamp |

### Contact form submitted (`contact.submitted`)

Route: `POST /contact`

| Field | Notes |
|---|---|
| `name` | Required |
| `email` | Required |
| `phone` | Optional |
| `subject` | Optional |
| `message` | Required |
| `website` | Honeypot — usually empty |

### Newsletter signup (`newsletter.subscribed`)

Route: `POST /newsletter`

| Field | Notes |
|---|---|
| `email` | Subscriber email |
| `submitted_at` | Server timestamp |

### Programme enquiry submitted (`program.enquiry_submitted`)

Route: `POST /programs/enquire`

| Field | Notes |
|---|---|
| `programme` | Programme name |
| `name` | Required |
| `email` | Required |
| `phone` | Required |
| `country` | Optional |
| `study_mode` | Optional |
| `qualification` | Human-readable label (e.g. Bachelor's Degree) |
| `message` | Optional |

## 4. Behaviour

- **Non-blocking:** Zapier fail ho to bhi user ko success dikhega; error `storage/logs/laravel.log` me log hoti hai.
- **Last status:** Admin table me har webhook ka last HTTP result (`success` / `failed` / `Never`) dikhta hai.
- **Disable:** Toggle OFF karke Zap rok sakte ho bina row delete kiye.
- **Multiple Zaps:** Same event par kai URLs — sabko parallel POST.

## 5. Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| Last status **Never** | Event fire nahi hua ya webhook disabled | Form submit karo; Enabled ON check karo |
| Last status **failed** | Galat URL, Zap off, ya Zapier error | URL dubara copy karo; Zapier dashboard check karo |
| Zapier me data nahi | Galat event select kiya | Admin me event key match karo |
| Test OK, real submit nahi | Disabled row ya galat event | Row enabled + sahi event |
| Purana contact Zap band | `.env` URL hat chuka | Admin me nayi row banao |

## 6. Developer note — naya trigger point

Agar **bilkul naya event** chahiye (jo abhi code me nahi hai):

1. [`app/Support/ZapierEvents.php`](../app/Support/ZapierEvents.php) me constant + `options()` label add karo
2. Jahan event hota hai wahan ek line:
   ```php
   app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::YOUR_EVENT, $payload);
   ```
3. Test likho; docs me fields table update karo

Existing events ke liye sirf admin me nayi webhook row — **code change nahi**.

## 7. Related

- Form emails (admin notification): [zoho-instruction.md](zoho-instruction.md) — Zoho **Mail** SMTP
- Newsletter list + confirmation email: same doc — Zoho **Campaigns** section

Zapier aur Zoho alag systems hain; dono parallel chal sakte hain.
