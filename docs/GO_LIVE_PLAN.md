# Go-Live Plan (Simple Path) — demo.vsinfosys.in → www.mbalondon.org.uk

**Target (canonical) domain: `https://www.mbalondon.org.uk`**
**Approach: koi migration nahi, koi naya setup nahi, koi 301 nahi.** Sirf domain map karo + `APP_URL` badlo.

---

## 0. Ek line mein

Demo site jis server par chal rahi hai, **wahi server aapka MilesWeb hai** — to `mbalondon.org.uk`
ko usi cPanel account mein addon domain ke roop mein add kar do, uska document root demo wale
`public/` folder par point kar do, SSL lagao, `.env` mein `APP_URL` badlo → **ho gaya**.

---

## 1. 🎯 Sabse important discovery (research se)

| Cheez | Proof |
|---|---|
| Demo server | `demo.vsinfosys.in` → **IP `103.102.234.3`** |
| Server hostname | PTR record: `103.102.234.3` → **`orient.herosite.pro`** |
| `herosite.pro` kiska hai | WHOIS registrant = **MilesWeb Admin, Nasik** (`email@milesweb.com`) |
| IP kiske AS mein hai | BGP: **AS135222 — MilesWeb Internet Services Pvt Ltd** |
| Server type | cPanel shared hosting (isi IP par 1000+ websites hosted hain) |

**Matlab:** demo site **pehle se aapke MilesWeb server par hai**. Naye server par kuch bhi copy karne
ki zaroorat nahi — na files, na database, na images (Cloudinary), na composer install.

### Doosre servers (reference)

| Service | IP | Host | Status |
|---|---|---|---|
| Purani WordPress site | `212.8.252.67` | **WorldStream** (`hosted-by-worldstream.net`) | Client ke purane provider ke paas |
| Student portal | `165.22.116.200` | **DigitalOcean** droplet (koi PTR nahi) | Kisi ke paas access nahi — Phase 2 |
| Email | — | **Zoho Mail** (India DC) | Chalta rahega, chhuna nahi hai |

---

## 2. Plan — 6 steps

| # | Step | Kaun | Time |
|---|---|---|---|
| 0 | Confirm karo ki MilesWeb cPanel = wahi account jisme demo hai | Aap | 5 min |
| 1 | Purani WP site ka poora backup | Aap (access hai) | 30–60 min |
| 2 | Client se DNS mein **sirf 2 A record** change karwana | Client / unka provider | 5 min + propagation |
| 3 | cPanel: addon domain add karo + docroot = demo wala `public/` + AutoSSL | Aap | 20 min |
| 4 | `.env` mein `APP_URL` + cache rebuild + `.htaccess` mein www rule | Aap | 15 min |
| 5 | Testing — site, forms, Zoho, Zapier | Aap | 1 ghanta |

**Go-live window: ~1 ghanta (raat mein karo). Koi data migration nahi.**

---

## 3. Step 0 — Pehle yeh confirm kar lo (5 min)

MilesWeb panel login karo aur check karo:

| Check | Kaise | Expected |
|---|---|---|
| Panel type | Login URL / dashboard title | **cPanel** (`:2083`) ya **WHM/reseller** (`:2087`) |
| Demo site isi account mein hai? | cPanel → **Domains** list | `demo.vsinfosys.in` dikhe |
| Server hostname | cPanel → right sidebar → *Server Information / Hostname* | `orient.herosite.pro` |
| App ka exact path | cPanel → **File Manager** → jahan `artisan` + `public/` hai | e.g. `/home/USER/demo.vsinfosys.in` |

### Agar cPanel hai (sabse zyada chance) ✅
Seedha Step 3 pe jao — **ek hi account mein dono domain**.

### Agar WHM / reseller hai (50 domains = alag-alag cPanel account)
To **dono domain ko ek hi cPanel account mein rakho** (jaise demo wale account mein).
Yani `mbalondon.org.uk` ko alag naya account mat banao — usi account mein addon domain banao jisme
`demo.vsinfosys.in` hai. Tab bhi **zero migration**.

> ⚠️ Agar client ke contract mein "alag cPanel account" zaroori hai, tabhi files + DB copy karna padega
> (~1–2 ghante ka extra kaam). Tab batao — alag runbook bana dunga.

---

## 4. Step 1 — Purani WordPress site ka backup (future safety)

Aapke paas WP admin + hosting panel ka access hai. Ye sab le lo:

| # | Kya | Kaise |
|---|---|---|
| 1 | **Full cPanel backup** | cPanel → *Backup / Backup Wizard* → **Full Backup** → download |
| 2 | **Files** (`wp-content` sabse important) | File Manager se `public_html` ka zip (ya FTP: FileZilla) |
| 3 | **Database** | phpMyAdmin → DB select → **Export** → `.sql` |
| 4 | **WP content export** | WP Admin → *Tools → Export → All content* → `.xml` (blogs future import ke liye) |
| 5 | **wp-config.php** | DB name/user/password note kar lo (DB credentials) |
| 6 | **Active plugins + theme list** | WP Admin → Plugins (kaun se the, pata rahe) |

> Backup apne local + Google Drive dono jagah rakho. Purani hosting **30 din cancel mat karna**.
> Email Zoho par hai, isliye purane host se mail ka koi backup nahi lena.

---

## 5. Step 2 — Client se kya mangwana hai (DNS) ⭐

Purana domain client ke purane provider ke paas hai (NS = `orderbox-dns.com` → BigRock/ResellerClub family;
hosting = WorldStream). **Hum sirf DNS mein 2 A record badalna chahte hain.**

### Minimum jo chahiye (Option A — best)
Client se (ya unke provider se) ye 2 changes karwao:

| Type | Name | Purani value | Nayi value |
|---|---|---|---|
| A | `@` | `212.8.252.67` | **`103.102.234.3`** |
| A | `www` | `212.8.252.67` | **`103.102.234.3`** |

Change se **1 din pehle TTL ko `21600` se `300` (5 min)** karwa dena — isse switch 5–30 min mein ho jayega.

### Bonus jo mang lo (Option B — ho sake to)
DNS zone ka **screenshot ya export** (poorainter list) — taaki hum verify kar sakein ki kuch miss to nahi ho raha.

### 🚫 Client/provider ko explicitly bolna — YE MAT CHHUNA
| Record | Kyun |
|---|---|
| `MX` → mx/mx2/mx3.zoho.com | Email Zoho par — band ho jayegi |
| `TXT` SPF, DKIM, `_dmarc` | Email deliverability |
| `CNAME webmail` → `mail.cs.zohohost.in` | Zoho webmail |
| `A studentportal` → `165.22.116.200` | Student portal alag server par chal raha hai |
| Domain transfer / registrar change | Zaroorat nahi |
| Nameservers (NS) | **Option A mein bilkul nahi** |

> Note: `studentportal` ka A record bhi isi DNS mein hai — Phase 2 (portal recovery) ke liye bhi
> yahi DNS access chahiye hoga. Isliye client se **permanent DNS access** (ya kam se kam
> "jab bolein tab change kar dena" ka commitment) le lo.

### Ready-to-send message (client / unke provider ke liye)

> Hi,
>
> We are moving the website **mbalondon.org.uk** to our new hosting server.
> Please **do not change the nameservers** — we only need two A records updated:
>
> | Type | Name | New value |
> |---|---|---|
> | A | `@` | `103.102.234.3` |
> | A | `www` | `103.102.234.3` |
>
> Also, please reduce the TTL of these two records to **300** one day before the change.
>
> **Please do NOT touch:** MX records (Zoho: mx / mx2 / mx3.zoho.com), any TXT records
> (SPF, DKIM, DMARC), the `webmail` CNAME, and the `studentportal` A record (165.22.116.200).
> Our email runs on Zoho and the student portal is hosted separately — both must keep working.
>
> Please also send us a screenshot/export of the full DNS zone so we can verify nothing is missed,
> and confirm the planned date/time for the switch (we suggest late night IST for lowest traffic).
>
> Kindly keep the current hosting active for at least 30 days after the switch.

---

## 6. Step 3 — cPanel par domain map karna

1. cPanel → **Domains** (ya *Addon Domains*) → **Create A New Domain**
2. Domain: `mbalondon.org.uk`
3. ⚠️ **Document Root** — cPanel jo suggest kare use change karke ye daalo:
   ```
   /home/USER/demo.vsinfosys.in/public
   ```
   (yahin pe demo site chal rahi hai — isi ko point karna hai)
4. **Add** dabao → `www.mbalondon.org.uk` alias automatic ban jata hai (agar na bane to *Aliases* se add kar do)
5. Check: **Domains** list mein `mbalondon.org.uk` dikhe aur uska document root `.../demo.vsinfosys.in/public` ho

**Result:** ab `demo.vsinfosys.in` aur `mbalondon.org.uk` **dono ek hi app serve karenge** —
same database, same content, same images. ✅ (Exactly aapka plan — koi naya setup nahi.)

> Is step ke baad bhi `demo.vsinfosys.in` chillata rahega. DNS switch ke baad dono kaam karenge.

---

## 7. Step 4 — SSL (AutoSSL)

> DNS propagation ke **baad** hi chalega — Let's Encrypt check karta hai ki domain isi server par point kar raha hai.

1. cPanel → **SSL/TLS Status** → **Run AutoSSL**
2. 2–10 min wait → `mbalondon.org.uk` + `www.mbalondon.org.uk` ke aage ✅ green
3. Verify: browser mein `https://www.mbalondon.org.uk` → lock icon, koi warning nahi
4. SSL Labs: https://www.ssllabs.com/ssltest/analyze.html?d=www.mbalondon.org.uk → grade **A**

**Agar fail ho:** propagation pending hai (wait karo) ya port 80 blocked hai → MilesWeb support se kehna.

**⚠️ Warning window:** DNS switch se lekar SSL issue hone tak **10–45 min** tak
"Your connection is not private" aayega. Isliye switch raat mein karo.

---

## 8. Step 5 — `.env` + cache + www rule

### 8.1 `.env` (cPanel → File Manager → `.env` → Edit, ya Terminal se `nano .env`)

Sirf **ek line** badalni hai:

```diff
- APP_URL=https://demo.vsinfosys.in
+ APP_URL=https://www.mbalondon.org.uk
```

Saath mein confirm kar lo (pehle se aise hi hone chahiye):
```env
APP_ENV=production
APP_DEBUG=false
SESSION_DOMAIN=null     # ⚠️ isme kabhi .mbalondon.org.uk mat likhna — admin login toot jayega
```

> ⚠️ **`APP_KEY` kabhi regenerate mat karna** — encrypted data + sessions invalid ho jayenge.

### 8.2 Cache rebuild (Terminal / SSH)

```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
cd ~/demo.vsinfosys.in

bash scripts/shared-hosting-optimize.sh
# ya manually:
# php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Kyun zaroori hai:** `APP_URL` = emails ke andar ke links (`GenericFormMail`). Page ke links
(`url()`, `asset()`, sitemap) request ke host se automatic ban jate hain — unhe chhune ki zaroorat nahi.

### 8.3 www + HTTPS rule (`public/.htaccess`)

Existing `<IfModule mod_rewrite.c>` block ke andar, `RewriteEngine On` ke theek baad ye 6 lines:

```apache
    # >>> GO-LIVE
    RewriteCond %{HTTPS} off
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    RewriteCond %{HTTP_HOST} ^mbalondon\.org\.uk$ [NC]
    RewriteRule ^ https://www.mbalondon.org.uk%{REQUEST_URI} [L,R=301]
    # <<< GO-LIVE
```

> Rule ko block ke **bahar** mat daalna. Agar aage Cloudflare/CDN lagao to `%{HTTPS} off` ki jagah
> `RewriteCond %{HTTP:X-Forwarded-Proto} !https` use karna (warna redirect loop).

---

## 9. Step 6 — Testing

### 9.1 Site
| Check | Expected |
|---|---|
| `https://www.mbalondon.org.uk/up` | 200 |
| Homepage + 5 pages (`/programs`, `/programs/{slug}`, `/blogs`, `/about-us`, `/contact`) | Load, console error nahi |
| Images | Cloudinary se https par load |
| `https://mbalondon.org.uk` (bina www) | 301 → `https://www.mbalondon.org.uk` |
| `http://` | 301 → `https://` |
| `/admin` | Login chale |
| Admin se kuch edit → frontend par dikhe | Cache flush ho raha hai |
| `https://www.mbalondon.org.uk/sitemap.xml` | XML, sab `<loc>` live domain ke |
| Mobile view | Theek |

### 9.2 Zoho (email) ⭐
| Check | Detail |
|---|---|
| Admin → **Zoho Settings → Enabled** | ✅ on |
| SMTP host | Zoho **India DC** (webmail → `mail.cs.zohohost.in`) → **`smtp.zoho.in`** (port 587, TLS). Default `smtp.zoho.com` hai — fail ho to `smtppro.zoho.in` try karo |
| Username / Password | Poora email (e.g. `admissions@mbalondon.org.uk`) + **App-specific password** (agar 2FA on hai) |
| Default recipient | `admissions@mbalondon.org.uk` ya jo chahiye |
| **Test:** contact form submit | Inbox + spam check karo, mail ke links `https://www.mbalondon.org.uk/...` ke hon |
| **Test:** program enquiry + newsletter + MBA landing form | Teeno |
| Zoho Campaigns (newsletter sync) | Admin → Zoho Campaigns settings → **region `in`** hona chahiye (India DC), list_key sahi, ek test subscribe karke Zoho list mein entry check karo |

### 9.3 Zapier ⭐
| Check | Kaise |
|---|---|
| Admin → **Zapier Webhooks** | Enabled webhooks ki list |
| Har webhook par **"Send test"** button | `Test sent` aana chahiye (ye `last_status = success` bhi update karta hai) |
| Zapier account → Zaps ON hain? | Zapier dashboard mein check |
| Real test: contact form bharo | Zapier → *Zap History* mein run dikhe |
| Events wire hain: `contact.submitted`, `newsletter.subscribed`, `program.enquiry_submitted` | Admin mein 3 webhooks hone chahiye |

---

## 10. Extra cheezein — jo aapne socha nahi (par karni padengi)

| # | Kaam | Kitna | Kyun |
|---|---|---|---|
| 1 | **DB mein purane demo-domain URLs dhundhna** | 10 min | Admin ke SEO tabs mein agar **Canonical URL** manual bhara hai to wahan `demo.vsinfosys.in` likha ho sakta hai. Niche SQL dekho. |
| 2 | **GA4 / GTM** | 10 min | Admin → SEO → *Custom head scripts* mein GA/GTM code hai to GA4 property mein `www.mbalondon.org.uk` allow hona chahiye |
| 3 | **Google Search Console** | 15 min | Property add + `sitemap.xml` submit (T+1 din) |
| 4 | **SPF (agar mail PHP `mail()` se bhej rahe ho)** | 5 min | `v=spf1 mx ip4:103.102.234.3 include:zohomail.com include:one.zoho.com -all`. Zoho SMTP use kar rahe ho to **zaroorat nahi** |
| 5 | **PTR / reverse DNS** | MilesWeb ticket | Sirf tab zaroori hai jab PHP `mail()` se bhej rahe ho (Zoho SMTP ho to skip) |
| 6 | **Demo domain ka duplicate-content risk** | 5 min (optional) | Niche dekho |
| 7 | **Admin panel public** | optional | `/admin` live domain par sabko dikhega. Chahe to Filament panel path badal sakte hain ya IP restrict |

### 1 — DB mein demo-domain URLs check karne ke queries (phpMyAdmin → SQL)

```sql
-- Settings (spatie) mein koi absolute URL?
SELECT * FROM settings WHERE payload LIKE '%vsinfosys%';

-- SEO canonicals
SELECT * FROM seo_metadata WHERE canonical_url LIKE '%vsinfosys%';
SELECT * FROM seo_metadata WHERE og_image_url LIKE '%vsinfosys%';

-- Programs / insights content mein hard-coded links?
SELECT id, slug FROM programs WHERE description LIKE '%vsinfosys%';
SELECT id, slug FROM insights WHERE content LIKE '%vsinfosys%';
```

Jo bhi row mile → admin panel se wahi value edit karke live domain daal do (ya khali chhod do —
khali hone par canonical automatic current URL ho jata hai).

### 6 — Demo domain (301 nahi chahiye, to ye karo) — optional

`public/.htaccess` mein:
```apache
# demo domain ko Google se door rakho (301 nahi, sirf noindex header)
<IfModule mod_headers.c>
    <If "%{HTTP_HOST} == 'demo.vsinfosys.in'">
        Header set X-Robots-Tag "noindex, nofollow"
    </If>
</IfModule>
```
> Filhaal `demo.vsinfosys.in` Google par indexed nahi mila (site: search empty aaya) — to ye optional hai.
> Agar `<If>` expression LiteSpeed par kaam na kare to simply chhod do, koi nuksan nahi.

---

## 11. Rollback

| Situation | Fix | Time |
|---|---|---|
| Site error / blank page | `php artisan optimize:clear && php artisan up` | 2 min |
| Kuch seriously toota | DNS A record wapas `212.8.252.67` | 5–10 min (TTL 300) |
| SSL warning | AutoSSL dobara run karo / MilesWeb ticket | 10 min |
| Admin login loop | `SESSION_DOMAIN` null check karo + `php artisan optimize:clear` | 5 min |

**Purani hosting 30 din tak chalu rakhna** — yahi sabse bada safety net hai.

---

## 12. Risk register

| Risk | Impact | Status |
|---|---|---|
| **301 nahi lagana** (aapka decision) | Purani site ke ~150 URLs (`/business-courses/*`, `/product/*`, ~40 blog posts) live domain par **404** denge → Google se wo pages hat jayenge, jo backlinks/traffic the wo loss hoga | ⚠️ Accepted. Chahe to baad mein 2 ghante mein redirect layer add kar sakte hain (code git history mein maujood hai) |
| Email band hona | Business critical | ❌ Rocord nahi chhune → zero risk. Bas Zoho SMTP verify karo |
| Student portal | Alag server + alag DNS record | ✅ Chhuna nahi hai |
| SSL warning window | 10–45 min | Raat mein switch karke minimize |
| DNS propagation | 5–30 min (TTL 300) | TTL kam karwana zaroori |
| Purani hosting cancel | Rollback khatam | 30 din ruko |

---

## 13. Phase 2 (baad mein) — studentportal.mbalondon.org.uk

Abhi ke liye sirf facts note kar lo, plan main site ke go-live ke **baad** banayenge:

| Cheez | Kya pata chala |
|---|---|
| URL | `https://studentportal.mbalondon.org.uk/customer/account/login` (auto-redirect) |
| IP | `165.22.116.200` — **DigitalOcean droplet** (koi PTR set nahi) |
| App | LMS/e-learning jaisa: page title "College meta title", logo `learn-mediafiles.s3.amazonaws.com/21648/...` → lagta hai koi hosted LMS (instance ID `21648`) |
| DNS | `studentportal` A record **client ke DNS provider** ke paas hai |
| Access | Purane provider ko bhi nahi pata kahan hosted hai |

**Next steps (Phase 2):** DNS access milne ke baad → droplet owner identify karna (DO account kiska hai),
port 22/21 check, agar access mil gaya to files + DB backup; nahi mila to naye stack par rebuild
(ya LMS vendor se instance recover karwana). Ye ek alag project hai.

---

## Appendix — Domain WHOIS (verified 25 Sep 2026, Nominet RDAP + whois.com)

| Field | Value |
|---|---|
| Domain | `mbalondon.org.uk` |
| Registry | **Nominet** (.uk registry) |
| Registered on | **26 Jun 2019** |
| **Expires on** | **26 Jun 2028** (≈ 2 saal 9 mahine baaki) |
| Last updated / renewed | 24 Jun 2025 (3 saal ke liye renew hua) |
| Status | **active** ✅ |
| **Registrar** | **PDR Ltd. d/b/a PublicDomainRegistry.com** (IANA ID `PDR-IN`) — Newfold Digital group |
| Registrant | 🔒 **Redacted for privacy** (Nominet policy) — WHOIS se owner ka naam nahi mila |
| Nameservers | `1234.earth/mars/mercury/venus.orderbox-dns.com` (**OrderBox** = PDR ka DNS platform) |

**Iska matlab (go-live ke liye):**

| Point | Action |
|---|---|
| Domain 2028 tak valid hai | ✅ Koi renewal/payment ki tension nahi. June 2028 se pehle renew karwana hoga |
| Registrar = PDR / PublicDomainRegistry | Ye **reseller registrar** hai — BigRock, ResellerClub, HostGator India, Bluehost India isi ke reseller hain |
| DNS `orderbox-dns.com` par hai | DNS **client ke domain reseller panel** (BigRock/ResellerClub type) se manage hota hai — **WorldStream (purana web host) se nahi** |
| Registrant redacted | Ownership WHOIS se confirm nahi ho sakti — client ko panel access dena hi padega |
| Renewal month = June | Har saal June mein renewal aata hai. WHOIS email active rakhna |

> **Client se poochhne ke liye:** "Domain kahan se register/manage hota hai — BigRock / ResellerClub /
> koi aur? Humein sirf 2 A record change karne hain, aap khud karwa dijiye ya panel ka access de dijiye."

Verify khud bhi kar sakte ho: https://www.nominet.uk/whois/lookup/?query=mbalondon.org.uk

---

## Appendix — Is repo mein maine kya add kiya

| File | Kya karta hai |
|---|---|
| `app/Http/Controllers/SitemapController.php` | `/sitemap.xml` — 25 static pages + programmes + blogs + news (URLs request host se bante hain, domain change par kuch karna nahi padta) |
| `resources/views/sitemap/index.blade.php` | XML view |
| `routes/web.php` | `/sitemap.xml` route (catch-all `/{slug}` se pehle) |
| `public/robots.txt` | `Sitemap:` line + `/admin`, `/livewire`, `/storage` crawl-block |
| `README.md`, `docs/SHARED_HOSTING.md` | Is plan ke links |

**Purane 301 redirect wala code hata diya gaya hai** (aapne bola tha 301 nahi chahiye) —
`config/redirects.php` aur `LegacyRedirects` middleware delete kar diye.
