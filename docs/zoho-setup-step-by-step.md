# Zoho Setup — Login ke baad ek-ek karke steps

> Ye guide is project ke code ke hisaab se likhi hai. Project me Zoho ke **2 alag parts** hain:
>
> | Part | Kya karta hai | Admin page |
> |---|---|---|
> | **Zoho Mail** (SMTP) | Contact form + Programme enquiry emails | `/admin/zoho-settings` |
> | **Zoho Campaigns** (API) | Footer **Newsletter** subscribe | `/admin/manage-zoho-campaigns-settings` |
>
> Koi `.env` edit nahi karni. Sab values **admin panel** me bharti hain.

---

## Step 0 — Login karo, REGION note karo (sabse important)

1. Browser me [https://mail.zoho.com](https://mail.zoho.com) kholo → login karo.
2. **URL bar dekho** — yahi aapka region hai:
   - `mail.zoho.com` → region **com (Global)**
   - `mail.zoho.in` → region **in (India)**
   - `mail.zoho.eu` → region **eu (Europe)**
   - `mail.zoho.com.au` → **com.au**, `mail.zoho.jp` → **jp**
3. Ye region baad me **3 jagah** chahiye hoga (Campaigns admin me, API console me, SMTP host me). Galat region = "Could not refresh access token" error.

> **Zoho Campaigns tab kholna:** [https://campaigns.zoho.com](https://campaigns.zoho.com) (region ke hisaab se `.in` / `.eu`).
> Agar pehli baar khol rahe ho to "Get Started / Activate" karke org choose karna padega.

---

# Part 1 — Zoho Mail (form emails)

Admin page: **Admin → Site Settings → Zoho Mail** (`/admin/zoho-settings`)

## Step 1.1 — App Password banao (yahi SMTP password hai)

1. Top-right **profile photo → My Account / Account** kholo (ya seedha [https://accounts.zoho.com](https://accounts.zoho.com)).
2. Left menu → **Security → App Passwords**.
   (Agar App Passwords nahi dikhe to pehle **Two-Factor Authentication ON** karo — Zoho app password tabhi deta hai.)
3. **Generate Password / New Password** button dabao:
   - **Application:** `Mail` (ya `Other` me likho `Laravel Website`)
   - **Device name:** kuch bhi, e.g. `Maverick Site`
4. Jo password screen pe dikhe use **copy karke notepad me save karo** — dobara nahi dikhega.
   → Ye value jayegi admin me **"App-specific password"** field me.

> ⚠️ Normal Zoho login password **mat** dalna — usse SMTP `535 Authentication Failed` error aata hai.

## Step 1.2 — Admin panel me Zoho Mail bhro

**Admin → Site Settings → Zoho Mail** pe jao aur ye bhro:

| Admin field | Kya dalna hai | Value kahan se mili |
|---|---|---|
| Enable Zoho SMTP | **ON** | — |
| Default recipient (admin inbox) | `admissions@yourdomain.com` (jahan form emails chahiye) | Aapka apna mailbox |
| From name | `Maverick Business Academy` | Aapki marzi |
| Reply-To | Khali chhodo (visitor ka email auto reply-to ban jata hai) | — |
| SMTP host | Region `com` → `smtp.zoho.com`, `in` → `smtp.zoho.in`, `eu` → `smtp.zoho.eu`. **Paid Workplace/Mail Plus custom domain** ho to `smtppro.zoho.com` | Step 0 ka region |
| Port | `587` | — |
| Encryption | `TLS` (port 587 ke saath) | — |
| Zoho mail domain | `yourdomain.com` (optional, sirf reminder) | — |
| Zoho username (full email) | `admissions@yourdomain.com` — **pura email**, sirf naam nahi | Aapka Zoho mailbox |
| App-specific password | Step 1.1 wala password paste karo | Step 1.1 |

**Save** karo. (Baad me dobara save karte waqt password **khali chhod do** — purana wala retain hota hai.)

## Step 1.3 — Test

1. Website pe **Contact page** kholo → ek real message submit karo.
2. Admin inbox + Zoho **Sent** folder check karo — email aana chahiye.
3. Na aaye to `storage/logs/laravel.log` me `FormMailer failed:` line dekho.

✅ Part 1 done. Ab Newsletter ke liye Zoho Campaigns.

---

# Part 2 — Newsletter — Zoho Campaigns

Admin page: **Admin → Site Settings → Zoho Campaigns** (`/admin/manage-zoho-campaigns-settings`)

Total **5 values** chahiye: `Region`, `List Key`, `Client ID`, `Client Secret`, `Refresh Token` — plus Zoho ke andar 2 cheezein (confirmation + welcome email). Ek-ek karke:

## Step 2.1 — Mailing List banao → LIST KEY copy karo

1. [campaigns.zoho.com](https://campaigns.zoho.com) login karo (region wala domain).
2. Left menu → **Contacts → Mailing Lists** (ya **Manage Lists**).
3. **Create Mailing List / New List** dabao:
   - List name: `Website Newsletter` (kuch bhi)
   - Description optional
4. List create hone ke baad us **list ka naam click karo** → upar/baadme **Setup** (ya ⚙️) tab kholo.
5. Wahan **List Key** dikhega (long alphanumeric string, e.g. `3ce4be261cd2b...`) → **copy karo**.
   → Ye value jayegi admin me **"Mailing list key"** field me.

## Step 2.2 — Sign-up form enable karo (double opt-in confirmation email ke liye)

Same list ke andar:

1. **Signup Forms / Associated Forms** section kholo (list ke Setup ke paas).
2. List ke liye **sign-up form ko Enable/Associate** karo.
3. Form settings me **Double Opt-in / Confirmation email** ON hai ya nahi dekh lo (default ON hi hota hai).
4. Isse jab website se koi subscribe karega, Zoho use **confirmation email** bhejega — user confirm karega tabhi list me add hoga. (Website ka code isi flow ke liye bana hai.)

> Subject/branding badalni ho: wahi signup form settings me **Confirmation email** customize karo.

## Step 2.3 — Welcome email ka Autoresponder banao

1. Left menu → **Automation → Autoresponders**.
2. **Create Autoresponder** dabao:
   - Name: `Welcome Newsletter`
   - **Associate list:** wahi `Website Newsletter` list (Step 2.1 wali)
   - Trigger: **When contact joins the list / on subscription confirmation**
3. Email content likho (welcome text + links) → **Activate/Enable** karo.
4. Ab har confirmed subscriber ko welcome email auto jayega — website code me iski zaroorat nahi, Zoho khud bhejega.

## Step 2.4 — API app banao → CLIENT ID + CLIENT SECRET milega

1. Naye tab me [https://api-console.zoho.com](https://api-console.zoho.com) kholo (region `in` ho to `api-console.zoho.in`, EU ho to `.eu`).
2. **Get Started / Add Client** dabao.
3. **Self Client** choose karo (sabse simple — redirect URL ki zaroorat nahi) → **Create**.
   *(Server-based App bhi chalega, neeche note dekho.)*
4. Client ban jaye to usko kholo:
   - **Client ID** copy karo ( `1000.XXXX...` format)
   - **Client Secret** copy karo
   → Ye dono admin me **"Client ID"** aur **"Client secret"** fields me jayenge.

## Step 2.5 — REFRESH TOKEN generate karo (sabse tricky step, dhyan se)

Self Client me ek **temporary code** banega, usse exchange karke refresh token milega:

### (a) Grant code generate karo

1. api-console.zoho.com me apne **Self Client** ke andar jao.
2. **Generate Code / Generate Code Tab** pe jao aur bhro:
   - **Scope:** `ZohoCampaigns.contact.CREATE`  ← bilkul yahi, copy-paste karo
   - **Time Duration:** `10` minutes default chhodo
   - **Description:** `Newsletter subscribe API`
3. **Generate** dabao → ek code milega ( `1000.XXXX...` format) → **turant copy karo** (10 min me expire ho jata hai).

### (b) Code ko refresh token me exchange karo

Terminal / cmd me ye chalao (apni values daal ke):

```bash
curl -X POST "https://accounts.zoho.com/oauth/v2/token" \
  -d "grant_type=authorization_code" \
  -d "client_id=CLIENT_ID_PASTE_KARO" \
  -d "client_secret=CLIENT_SECRET_PASTE_KARO" \
  -d "code=GENERATED_CODE_PASTE_KARO"
```

- Region `in` ho to URL me `accounts.zoho.in`, EU ho to `accounts.zoho.eu`.
- Windows pe curl na ho to PowerShell `Invoke-RestMethod` ya postman bhi chalega (POST, form-data).

Response me aisa milega:

```json
{
  "access_token": "1000.xxxx...",
  "refresh_token": "1000.yyyy...",   ← YE COPY KARO
  "api_domain": "https://www.zohoapis.com",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

→ `refresh_token` ki value jayegi admin me **"Refresh token"** field me.
→ Refresh token **sirf ek baar** milta hai — kahin safe save kar lo. Kho gaya to Step 2.5 dobara karo.

> **Note — Server-based App choose kiya ho:**
> 1. Redirect URI me kuch bhi daalo (e.g. `https://yourdomain.com/zoho-callback`) — bas yaad rakho.
> 2. Browser me ye URL kholo:
>    `https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id=YOUR_CLIENT_ID&scope=ZohoCampaigns.contact.CREATE&access_type=offline&redirect_uri=https://yourdomain.com/zoho-callback`
>    → **`access_type=offline` hona zaroori hai** warna refresh token nahi milega.
> 3. Accept karo → redirect hoke URL me `code=...` dikhega → copy karo → upar wali (b) curl me `redirect_uri` line bhi add karke exchange karo.

## Step 2.6 — Admin panel me Zoho Campaigns bhro

**Admin → Site Settings → Zoho Campaigns** pe jao:

| Admin field | Kya dalna hai | Value kahan se mili |
|---|---|---|
| Enable Zoho Campaigns sync | **ON** | — |
| Zoho data center | `com` / `in` / `eu` — **Step 0 wala region** | Login URL |
| Mailing list key | List Key paste karo | Step 2.1 |
| Contact source label | `Website Footer` (default theek hai) | — |
| Client ID | Paste karo | Step 2.4 |
| Client secret | Paste karo | Step 2.4 |
| Refresh token | Paste karo | Step 2.5 |

**Save** karo → upar **"Test connection"** button dabao:
- ✅ *Connected to Zoho Campaigns successfully* = credentials + region sahi hain.
- ❌ *Could not refresh access token* = region galat hai, ya token/secret me galti hai — Step 0 aur 2.5 check karo.

## Step 2.7 — End-to-end test (asli proof)

1. Website ka **footer** kholo → newsletter box me apna email dalo → submit.
2. Page pe message aayega: *"Almost there — please check your inbox and confirm your subscription."* → iska matlab API call **success** hua.
3. Apne email inbox me **Zoho confirmation email** aayega → usme **Confirm** link click karo.
4. Zoho Campaigns → apni list kholo → tumhara contact **Active** dikhna chahiye.
5. Thodi der me **welcome autoresponder** (Step 2.3) bhi email aayega.
6. Admin inbox me *"New newsletter signup"* notification bhi aana chahiye (ye Zoho Mail / Part 1 se aata hai).

Kuch fail ho to `storage/logs/laravel.log` me `Zoho Campaigns` lines dekho.

---

## Value cheat-sheet (sab ek jagah)

| # | Value | Kahan se milegi | Admin field |
|---|---|---|---|
| 1 | Region (`com`/`in`/`eu`) | Zoho login ka URL | Zoho Mail (SMTP host) + Zoho Campaigns (Data center) |
| 2 | App-specific password | accounts.zoho.com → Security → App Passwords | Zoho Mail → App-specific password |
| 3 | SMTP host / port | Region se (`smtp.zoho.com` etc.), `587`+TLS | Zoho Mail |
| 4 | Mailing list key | Campaigns → Contacts → Mailing Lists → list → **Setup** | Zoho Campaigns → Mailing list key |
| 5 | Client ID + Secret | api-console.zoho.com → Self Client | Zoho Campaigns → OAuth |
| 6 | Refresh token | Self Client → Generate Code (scope `ZohoCampaigns.contact.CREATE`) → curl se exchange | Zoho Campaigns → Refresh token |

## Deliverability (emails spam me na jayein — ek baar karna)

- Zoho Campaigns → **Settings → Sender Address / Domain Authentication** → apna domain verify karo aur **DKIM/SPF** DNS records domain me lagao.
- Zoho Mail → **Domains** (ya admin.zoho.com) → Mail ke liye bhi SPF/DKIM setup ho — usually already hota hai jab domain verify kiya tha.

## Common problems

| Problem | Wajah | Fix |
|---|---|---|
| Subscribe log me **HTTP 401 HTML page** | Naya Zoho account (new Campaigns UI — URL me `/newui/`) classic API pe allowed nahi | Admin → Zoho Campaigns → **API endpoint = Marketing Automation** + naya token scope `ZohoMarketingAutomation.contact.CREATE` se |
| Test connection: `INVALID_OAUTHSCOPE` | Scope endpoint se match nahi | Classic: `ZohoCampaigns.contact.CREATE` / Marketing Automation: `ZohoMarketingAutomation.contact.CREATE` |
| Test connection fail / token refresh fail | Region mismatch ya galat token | Data center field = login URL ka region; Step 2.5 dobara |
| `invalid_code` curl pe | Code expire (10 min) / reuse | Naya code generate karke turant exchange karo |
| Subscribe pe "success" par email nahi aata | Signup form list pe enabled nahi | Step 2.2 karo |
| Contact list me nahi dikh raha | User ne confirmation click nahi kiya (double opt-in) | Apna inbox/spam check karo |
| Welcome email nahi gaya | Autoresponder inactive ya galat list pe | Step 2.3 check karo |
| SMTP `535` error (forms) | Normal password used | Step 1.1 — App Password |
