# Go-Live Plan — demo.vsinfosys.in → www.mbalondon.org.uk

**Target (canonical) domain: `https://www.mbalondon.org.uk`**

**Goal:** Jo naya Laravel site aaj `https://demo.vsinfosys.in` par chal rahi hai, wahi code + content
`https://www.mbalondon.org.uk` par serve ho (naye hosting server par), aur purani WordPress site replace ho jaye.

**Approach (short version):**
1. Naye server (cPanel) par `mbalondon.org.uk` ko **addon domain** ke roop mein add karo, document root = wahi `public/` folder jahan abhi demo chal raha hai.
2. DNS mein **sirf A record** badlo (`@` aur `www` → `103.102.234.3`). MX / TXT / CNAME / `studentportal` ko **haath mat lagao** — email Zoho par chal rahi hai.
3. cPanel AutoSSL se SSL certificate lagao.
4. `.env` mein `APP_URL` badlo + Laravel caches rebuild karo.
5. Purane WordPress URLs ke liye 301 redirects (code mein already taiyaar hai — neeche dekho).
6. Demo domain ko 301 se live domain par bhej do.

**Ek hi app instance, do domain.** Demo aur live dono ek hi folder se serve honge — alag-alag copy nahi.
Isse content sync ka koi issue nahi hoga. (Baad mein demo ko redirect kar denge.)

---

## 1. Aaj kya chal raha hai (verified 25 Sep 2026)

| Cheez | Purani live site | Nayi site (demo) |
|---|---|---|
| Domain | `mbalondon.org.uk` + `www` | `demo.vsinfosys.in` |
| Platform | WordPress + Elementor + WooCommerce + MasterStudy LMS | Laravel 13 + Filament 3 + Livewire |
| Server IP | `212.8.252.67` | `103.102.234.3` (VS Infosys, cPanel) |
| DNS | NS = `1234.*.orderbox-dns.com` (BigRock / ResellerClub family) | `vsinfosys.in` Cloudflare par; `demo` = direct A record |
| Email | **Zoho Mail** (`mx.zoho.com`, `mx2`, `mx3`), webmail → `mail.cs.zohohost.in` (India DC) | Same Zoho (app se SMTP) |
| Media | `mbalondon.org.uk/wp-content/uploads/...` | **Cloudinary** (`res.cloudinary.com/i08gwudw`) |
| Content | ~40 blog posts, ~50 `business-courses/*`, ~60 `product/*` (shop) | 68 programmes, ~10+ blogs, news, events |
| Subdomain | `studentportal.mbalondon.org.uk` → `165.22.116.200` (**alag server — bachana hai**) | — |

**Important:** media Cloudinary par hai, server par nahi. Isliye images migrate karne ki zaroorat nahi — bas
Cloudinary keys naye `.env` mein hone chahiye (woh pehle se hain, kyunki demo par images load ho rahe hain).

---

## 2. Kaun kya karega

| Kaam | Kaun |
|---|---|
| Code changes (redirects, sitemap, robots) | ✅ **Ho gaya** (is branch par, neeche Appendix D) |
| cPanel par addon domain + SSL | **VS Infosys** (ya aap, agar cPanel login hai) |
| DNS A record change | **VS Infosys** (aapne bola tha) |
| Laravel `.env` + cache commands | **Aap / developer** (SSH ya cPanel Terminal) |
| Testing + Search Console | **Aap** (checklist neeche) |
| Email (Zoho) settings | **Aap** (admin panel se) |

---

## 3. Timeline (ek nazar mein)

| Kab | Kya |
|---|---|
| **T-2 din** | TTL 21600 → 300 kar do (DNS panel). Backups lo. |
| **T-1 din** | Yeh branch `main` mein merge karo. (Changes deploy karne ke liye) |
| **T-0 (go-live din, kam traffic wale time — e.g. raat 11 baje – 2 baje IST)** | cPanel addon domain → DNS A record → AutoSSL → `.env` + cache → testing |
| **T+1 din** | Demo 301, Search Console sitemap submit, form/email test |
| **T+7 din** | GSC 404 report dekhkar redirect map mein entries add karo |
| **T+30 din** | Purani hosting cancel karo (tab tak rakhna hai — backup + rollback ke liye) |

---

## 4. Phase 0 — Go-live se 1–2 din pehle (PREP)

### 4.1 DNS TTL kam karo ⏱️ (sabse important)
DNS panel (jahan `mbalondon.org.uk` ke records hain — BigRock/ResellerClub/VS Infosys) mein jao:

| Type | Name | Value | TTL |
|---|---|---|---|
| A | `@` | `212.8.252.67` | `21600` → **`300`** |
| A | `www` | `212.8.252.67` | `21600` → **`300`** |

> TTL 300 (5 min) karne se switchover ke waqt propagation 5–30 min mein ho jayega.
> Nahi kiya to purani site 6 ghante tak kuch users ko dikhti rahegi.

### 4.2 Full backup lo (koi bhi change karne se pehle)
- [ ] **Purani WP site:** cPanel → Backup Wizard → **Full Backup** download karke apne paas rakho
      (ya WP admin se UpdraftPlus/All-in-One WP Migration export).
- [ ] **Nayi site ka database:** cPanel → phpMyAdmin → app ka DB → **Export** (`.sql`).
- [ ] **Nayi site ka `.env`:** `cp .env .env.backup-$(date +%F)` (ya cPanel File Manager se download).
- [ ] **Cloudinary:** kuch karne ki zaroorat nahi (Cloudinary apna backup rakhta hai), par admin se
      Media library ka CSV export le lo agar chaho.
- [ ] **DNS zone ka screenshot/export** (Appendix A) — agar nameservers move hue to yehi Bachhav hai.

### 4.3 Code merge + deploy
```bash
# local / jahan bhi aap kaam karte ho
git checkout main && git pull origin main
git merge arena/01a0d763-maverick
git push origin main
```

Server par (cPanel → **Terminal**, ya SSH):
```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
alias composer='/opt/cpanel/ea-php83/root/usr/bin/php /usr/local/bin/composer'

cd ~/demo.vsinfosys.in
git pull origin main
composer install --no-dev --optimize-autoloader --no-interaction
bash scripts/shared-hosting-optimize.sh
```

Verify (deploy ke turant baad, demo domain par hi):
- [ ] `https://demo.vsinfosys.in/up` → 200
- [ ] `https://demo.vsinfosys.in/sitemap.xml` → XML dikhe, `<loc>` me `demo.vsinfosys.in` ho
- [ ] `https://demo.vsinfosys.in/business-courses/mba-anglia-ruskin-university` → 301 → `/programs/...` ya `/programs`
- [ ] `https://demo.vsinfosys.in/wp-admin/` → 301 → homepage
- [ ] `https://demo.vsinfosys.in/about-us` → **200** (redirect nahi hona chahiye — real page hai)

> Agar koi redirect galat lage → `.env` mein `LEGACY_REDIRECTS_ENABLED=false` daal kar
> `php artisan config:cache` — sab kuch normal ho jayega, phir `config/redirects.php` theek karo.

---

## 5. Phase 1 — cPanel par domain add karo (VS Infosys / aap)

> Ye step **DNS change se pehle** kar sakte ho — isse site par koi asar nahi padta.

1. cPanel → **Domains** (ya *Addon Domains*)
2. **Create A New Domain** → domain: `mbalondon.org.uk`
3. ⚠️ **Document Root** — cPanel automatically `mbalondon.org.uk` suggest karega.
   Use **change** karke yeh daalo (apna cPanel username `USER` jagah):
   ```
   /home/USER/demo.vsinfosys.in/public
   ```
   (ya jo bhi path hai jahan app ka `public/` folder hai — wahi jahan abhi demo chal raha hai)
4. Subdomain/FTP fields jo bhi aaye — default rehne do.
5. **Create/Add** dabao.
6. Confirm: **Domains** list mein `mbalondon.org.uk` dikhe aur uska document root `.../demo.vsinfosys.in/public` ho.
7. `www.mbalondon.org.uk` alias banna chahiye (cPanel automatic karta hai). Agar na bane to
   **Aliases** (ya *Parked Domains*) mein `www.mbalondon.org.uk` add karke uska bhi document root wahi point karo.

**Check:** is step ke baad bhi `demo.vsinfosys.in` chalta rahega (dono ek hi folder serve kar rahe hain). ✅

---

## 6. Phase 2 — DNS change (VS Infosys)

### ✅ Recommended (safest): nameservers wahin rakho, sirf A record badlo

DNS panel mein jao (`mbalondon.org.uk` ke records) aur **sirf ye 2 cheezein** badlo:

| Type | Name | Purani value | Nayi value |
|---|---|---|---|
| A | `@` | `212.8.252.67` | **`103.102.234.3`** |
| A | `www` | `212.8.252.67` | **`103.102.234.3`** |

**🚫 IN KO HAATH MAT LAGAO** (galati se email band ho jayegi):
- `MX` → `mx.zoho.com` / `mx2.zoho.com` / `mx3.zoho.com`
- `TXT` → SPF (`v=spf1 mx ip4:... include:zohomail.com ...`)
- `TXT` → `k=rsa; p=MIGfMA0...` (domain key)
- `TXT` → `_dmarc`
- `CNAME` → `webmail` → `mail.cs.zohohost.in`
- `A` → `studentportal` → `165.22.116.200`

### ❌ Avoid (jab tak zaroori na ho): nameservers (NS) move karna
Agar VS Infosys keh raha hai "NS hamare server par point kar do", to **pehle poori zone ka
screenshot/export lo** (Appendix A) aur unhe likh kar do ki **MX + TXT + CNAME + studentportal
sab copy karne hain**. Ek record miss hua to Zoho Mail band.

> NS move ke baad propagation 24–48 ghante tak chal sakta hai. A-record approach mein 5–30 min.

### Propagation check
- https://www.whatsmydns.net → `mbalondon.org.uk` → type A → sab jagah `103.102.234.3`
- https://dnschecker.org → same
- Local machine se (terminal): `nslookup mbalondon.org.uk` (Windows) / `dig mbalondon.org.uk +short` (Mac/Linux)

---

## 7. Phase 3 — SSL certificate (cPanel AutoSSL)

> Ye DNS propagation ke **baad** hi kaam karega — Let's Encrypt check karta hai ki domain isi server par point kar raha hai.

1. cPanel → **SSL/TLS Status** → **Run AutoSSL** (ya *SSL/TLS* → *Manage AutoSSL*)
2. 2–10 min wait karo → `mbalondon.org.uk` aur `www.mbalondon.org.uk` dono ke aage ✅ (green) aana chahiye
3. Verify browser se: `https://www.mbalondon.org.uk` → lock icon, koi warning nahi
4. SSL Labs test: https://www.ssllabs.com/ssltest/analyze.html?d=www.mbalondon.org.uk → grade **A**

**Agar AutoSSL fail ho:**
- Domain abhi bhi purane IP par resolve ho raha hai → propagation ka wait karo, phir dobara run karo
- Port 80 block hai → VS Infosys se kehna ki port 80 open rakhe (Let's Encrypt validation ke liye)
- cPanel "DCV" error → VS Infosys ko bolo

**⚠️ Certificate warning ka chhota window:** DNS switch se lekar SSL issue hone tak (10–45 min)
`https://mbalondon.org.uk` par "Your connection is not private" aayega. Isliye switchover
**raat ke time** karo. Is window mein `http://` bhi kaam karega (certificate ke bina).

---

## 8. Phase 4 — Laravel config + caches

cPanel → **Terminal** (ya SSH):

```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
cd ~/demo.vsinfosys.in

# 1. .env edit karo (cPanel File Manager se bhi kar sakte ho: .env → Edit)
#    Ye 3 lines zaroori hain:
#      APP_ENV=production
#      APP_DEBUG=false
#      APP_URL=https://www.mbalondon.org.uk
#    ⚠️ SESSION_DOMAIN mat chhero (null rehne do) — set karne se demo/admin login toot jayega.
#    ⚠️ APP_KEY kabhi regenerate mat karna.

# 2. Caches rebuild (config/route/view + file cache flush)
bash scripts/shared-hosting-optimize.sh
```

Agar script allowed nahi hai to manually:
```bash
php artisan optimize:clear
php artisan event:cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Kyun zaroori hai:**
- `APP_URL` = emails mein jo links jaate hain (`GenericFormMail`) wahi use karta hai → live domain hona chahiye
- Page ke andar ke links (`url()`, `asset()`) request ke host se bante hain — wo automatic sahi ho jayenge
- `route:cache` naye `/sitemap.xml` route ke liye zaroori hai
- `optimize:clear` 24-hour `PublicContentCache` ko flush karta hai

---

## 9. Phase 5 — Canonical + redirect rules (.htaccess)

`public/.htaccess` mein **existing** `<IfModule mod_rewrite.c>` block ke andar,
`RewriteEngine On` ke theek baad (trailing-slash rule se **pehle**) ye 6 lines insert karo:

```apache
    RewriteEngine On

    # >>> GO-LIVE: HTTP -> HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # >>> GO-LIVE: non-www -> www (sirf main domain ke liye; demo/staging ko chhod kar)
    RewriteCond %{HTTP_HOST} ^mbalondon\.org\.uk$ [NC]
    RewriteRule ^ https://www.mbalondon.org.uk%{REQUEST_URI} [L,R=301]
    # <<< GO-LIVE

    # Handle Authorization Header
    ...
```

> 🚫 Rule ko `<IfModule mod_rewrite.c>` block ke **bahar** mat daalna — purane block ke bahar
> `RewriteEngine On` dobara likhne se LiteSpeed/Apache par doosri rules skip ho sakti hain.
>
> 🌐 Agar aage chalkar domain Cloudflare ya kisi CDN ke peeche jaye to `%{HTTPS} off` ki jagah
> `RewriteCond %{HTTP:X-Forwarded-Proto} !https` use karna (warna redirect loop).

Test:
| URL | Expected |
|---|---|
| `http://mbalondon.org.uk/` | 301 → `https://www.mbalondon.org.uk/` |
| `https://mbalondon.org.uk/programs` | 301 → `https://www.mbalondon.org.uk/programs` |
| `https://www.mbalondon.org.uk/` | 200 (koi redirect nahi) |

---

## 10. Phase 6 — Go-live testing checklist

Basic:
- [ ] `https://www.mbalondon.org.uk/up` → **200**
- [ ] Homepage load, koi console error nahi (F12 → Console)
- [ ] Logo/images load ho rahe hain (Cloudinary `res.cloudinary.com` URLs, https)
- [ ] 5–6 important pages: `/programs`, `/programs/{ek-slug}`, `/blogs`, `/blogs/{ek-slug}`, `/about-us`, `/contact`
- [ ] Mobile view theek hai
- [ ] Admin: `https://www.mbalondon.org.uk/admin` → login ho raha hai
- [ ] Admin se ek cheez edit karke save karo → frontend par dikhe (cache flush ho raha hai)

SEO:
- [ ] `https://www.mbalondon.org.uk/sitemap.xml` → XML, sab `<loc>` `https://www.mbalondon.org.uk/...` se shuru hon
- [ ] `https://www.mbalondon.org.uk/robots.txt` → `Sitemap:` line sahi domain ke saath
- [ ] Kisi page par right-click → View Source → `<link rel="canonical">` mein live domain ho
- [ ] Puraani URL test (redirect-checker.org ya httpstatus.io se):
      - `/business-courses/mba-anglia-ruskin-university` → 301 → `/programs/...` ya `/programs`
      - `/product/msc-accounting-finance` → 301 → `/programs/...`
      - `/shop` → 301 → `/programs`
      - `/wp-content/uploads/...` → 301 → `/`
      - `/university-overview` → 301 → `/about-us`
- [ ] Koi bhi **nayi** URL 404 na de (e.g. `/programs/mba-business-management`)

Forms & email (sabse zyada miss hota hai):
- [ ] Contact form submit → inbox mein mail aaye (spam folder bhi dekho)
- [ ] Program enquiry form
- [ ] Newsletter subscribe
- [ ] `https://www.mbalondon.org.uk/online-mba-masters-uae` landing ka enquiry form
- [ ] Mail ke andar jo links hain, wo `https://www.mbalondon.org.uk/...` ke hon (na ki demo domain ke)

SSL / security:
- [ ] SSL Labs → grade A
- [ ] `http://` → `https://` redirect
- [ ] `APP_DEBUG=false` hai (koi page jaan-bujhkar todkar check karo → stack trace nahi dikhna chahiye)

---

## 11. Phase 7 — Go-live ke baad (24–48 ghante)

### 11.1 Demo domain ko redirect karo
Jab upar sab ✅ ho jaye, tab `public/.htaccess` ke **usi** GO-LIVE block mein (www rule ke neeche) ye add karo:

```apache
# -------------------------------------------------------------------
# 3) demo.vsinfosys.in -> live (path preserve ke saath)
# -------------------------------------------------------------------
RewriteCond %{HTTP_HOST} ^demo\.vsinfosys\.in$ [NC]
RewriteRule ^(.*)$ https://www.mbalondon.org.uk/$1 [L,R=301]
```

Ya cPanel → **Redirects** → Type: Permanent (301) → `demo.vsinfosys.in` →
`https://www.mbalondon.org.uk/` → **Wild Card Redirect** tick karein.

> 🚫 **Demo ka DNS A record delete mat karna.** Record hataya to redirect fire hi nahi hoga.
> Domain sirf tab hatana hai jab aap demo ko bilkul band karna chahte ho.

### 11.2 Search Console + Analytics
1. https://search.google.com/search-console → **Add property**
   - Best: **Domain property** (`mbalondon.org.uk`) — DNS TXT se verify, subdomains bhi cover
   - Agar purani property already hai to wahi use karo
2. **Sitemaps** → `sitemap.xml` submit karo (bas `sitemap.xml` likho, full URL nahi)
3. **URL Inspection** → homepage + 2–3 aur pages → **Request Indexing**
4. https://www.bing.com/webmasters → *Import from Google Search Console*
5. **GA4 / GTM:** Admin → SEO settings → Custom head scripts mein GA/GTM code hai to check karo ki
   GA4 property mein `www.mbalondon.org.uk` data stream allowed hai. (Agar code nahi hai to add karne ka time hai.)

### 11.3 Email deliverability (Zoho)
Forms ke mails Zoho SMTP se bhejne chahiye — tab SPF badalne ki zaroorat nahi:
- Admin → **Site Settings → Zoho Settings** (ya jo page hai):
  - `Enabled` = ✅
  - SMTP host = **`smtp.zoho.in`** ⚠️ (aapka Zoho **India DC** par hai — `webmail.mbalondon.org.uk` → `mail.cs.zohohost.in`)
    - Agar auth fail ho to `smtppro.zoho.in` try karo (organization/domain accounts ke liye)
  - Port = `587`, Encryption = `TLS` (ya port `465` + `SSL`)
  - Username = poora email (e.g. `admissions@mbalondon.org.uk`)
  - Password = Zoho password ya **App-specific password** (agar 2FA on hai)
- **Agar aap PHP `mail()` use kar rahe ho** (Zoho SMTP off), to SPF update zaroori hai:
  ```
  v=spf1 mx ip4:103.102.234.3 include:zohomail.com include:one.zoho.com -all
  ```
  aur VS Infosys se **PTR/rDNS** set karwao (`103.102.234.3` → `mbalondon.org.uk`), warna mail spam mein jayegi.
- Purane server ke IPs (`128.204.199.227`, `128.204.199.38`) purani hosting cancel karne ke baad SPF se hata dena.
- Test: https://www.mail-tester.com (score 8+ chahiye)

### 11.4 Monitoring (pehle 7 din)
- Roz subah 2 min: `storage/logs/laravel-*.log` check karo (cPanel File Manager)
- Admin → Dashboard widgets: koi error spike?
- https://search.google.com/search-console → **Pages → Not found (404)** → jo purani URLs aayein,
  unhe `config/redirects.php` ke `exact` array mein add karo → `php artisan config:cache`

### 11.5 Purani hosting
- **30 din tak cancel mat karo.** Backup, rollback aur "oh, yeh file toh reh gayi" ke liye zaroori hai.
- Cancel karne se pehle: purane server se `/wp-content/uploads/` ka backup le lo (archive ke liye).
- Purani site ko "park" kar sakte ho (WP ko maintenance mode mein daal do) — DNS to ab naye server par hai.

---

## 12. Rollback plan (agar kuch toot jaye)

**Level 1 — Laravel issue (site dikhti hai par error):**
```bash
php artisan optimize:clear
php artisan up
```
Phir issue fix karo, dobara `bash scripts/shared-hosting-optimize.sh`.

**Level 2 — Redirect loop / galat redirects:**
`.env` mein `LEGACY_REDIRECTS_ENABLED=false` → `php artisan config:cache`.

**Level 3 — Poori site down / SSL issue:**
DNS panel mein A record wapas `212.8.252.67` kar do (TTL 300 ki wajah se 5–10 min mein purani WP site wapas).
Phir shanti se issue fix karo.

**Level 4 — Admin lockout:**
cPanel → phpMyAdmin → DB mein `users` table check; ya `php artisan tinker` se user create.
(Emergency: `php artisan cache:clear` + `php artisan up`.)

---

## 13. Pending decision — purana content (blogs / courses / shop)

Aapne kaha "baad mein decide karenge". Filhaal safety net yeh hai:

| Purani URL | Ab kahan jaati hai |
|---|---|
| `/business-courses/{slug}` | Programme slug match hone par `/programs/{slug}`, warna `/programs` |
| `/product/{slug}` | Upar wale jaisa |
| `/university/*` | `/global-university-partners` |
| `/shop`, `/cart`, `/product-category/*` | `/programs` |
| `/wp-admin`, `/wp-content`, feed, archives, `/author/*`, `/category/*` | Homepage ya `/blogs` |
| **Purane blog posts** (`/{post-slug}`) | ⚠️ Filhaal **404** — neeche options |
| **WooCommerce checkout/payment** | ❌ Naye app mein **hai hi nahi** — neeche options |

**Option A (recommended for now):** Purane blogs ko redirect kar do `/blogs` par.
`config/redirects.php` ke `exact` array mein entries add karni padengi (~40).
Bolo to main purani sitemap se poori list nikal kar file bana deta hoon.

**Option B:** Purane blog posts ko naye app mein import kar do (WP XML export → `blogs` Insight rows).
_Yeh ek alag kaam hai — batao to alag plan banata hoon._ Traffic/links wale posts ke liye best hai.

**Option C:** Shop/checkout chahiye → naye Laravel app meinpayment gateway integrate karna padega.
**Bada feature** — filhaal scope se bahar. Agar admissions WhatsApp/CRM se handle ho rahe hain
(jo live site par lag raha hai — "ENROLL NOW" WhatsApp par jaata hai), to shop ki zaroorat nahi.

---

## Appendix A — DNS inventory (aaj ke records — backup ke liye)

| Type | Name | Value | Go-live par |
|---|---|---|---|
| NS | `@` | `1234.venus/earth/mars/mercury.orderbox-dns.com` | ⚠️ waise hi rehne do |
| A | `@` | `212.8.252.67` | ✏️ → `103.102.234.3` |
| A | `www` | `212.8.252.67` | ✏️ → `103.102.234.3` |
| MX | `@` | `10 mx.zoho.com`, `20 mx2.zoho.com`, `50 mx3.zoho.com` | 🚫 touch mat karo |
| TXT | `@` | `v=spf1 mx ip4:128.204.199.227 ip4:128.204.199.38 include:zohomail.com include:one.zoho.com -all` | ✏️ baad mein (naya IP add / purane hatana) |
| TXT | `@` | `k=rsa; p=MIGfMA0GCSqGSIb3...` (domain key) | 🚫 |
| TXT | `_dmarc` | `v=DMARC1;p=none; sp=none; rua=mailto:international@mbalondon.org.uk; ruf=...` | 🚫 (optional: `p=quarantine` baad mein) |
| CNAME | `webmail` | `mail.cs.zohohost.in` | 🚫 |
| A | `studentportal` | `165.22.116.200` | 🚫 (alag server — portal wahin chalega) |
| — | `mail`, `ftp`, `cpanel`, `autodiscover`, `portal`, `crm`, `api`, `shop`, `lms`, ... | **exist nahi karte** | — |

> Ye table Google DNS se 25 Sep 2026 ko verify kiya gaya hai. Panel kholne ke baad ek baar
> actual records se mila lo — kuch hidden records (jaise Zoho verification TXT) ho sakte hain.

---

## Appendix B — VS Infosys ko bhejne ke liye ready message (English)

> Hi,
>
> We are migrating our website **mbalondon.org.uk** (currently WordPress on another host,
> IP 212.8.252.67) to your server where our new application is already running
> (`demo.vsinfosys.in`, IP **103.102.234.3**). Please do the following:
>
> 1. **Add `mbalondon.org.uk` as an add-on domain on our cPanel account**, with:
>    - Document root: `/home/<USER>/demo.vsinfosys.in/public`
>      (the SAME `public/` folder that currently serves `demo.vsinfosys.in`)
>    - Include the `www` alias.
>
> 2. **Issue SSL** (AutoSSL / Let's Encrypt) for `mbalondon.org.uk` and `www.mbalondon.org.uk`.
>    Please keep **port 80 open** so Let's Encrypt validation works.
>
> 3. **DNS:** please do **NOT** change the nameservers. In the current DNS zone
>    (orderbox / BigRock) please change only these two records:
>    - `A  @    103.102.234.3`   (was 212.8.252.67)
>    - `A  www  103.102.234.3`   (was 212.8.252.67)
>
>    **Do not modify:** MX records (Zoho: mx/mx2/mx3.zoho.com), TXT records (SPF, DMARC,
>    domain key), the `webmail` CNAME, and the `studentportal` A record (165.22.116.200).
>    Our email runs on Zoho and must keep working.
>
> 4. Please set **PTR / reverse DNS** for `103.102.234.3` → `mbalondon.org.uk` (for email delivery).
>
> 5. Please confirm the PHP version is **8.3** and that `/up` returns HTTP 200.
>
> Please confirm once done, and suggest a low-traffic window for the DNS switch.
> We will keep the old hosting active for 30 days as a fallback.

---

## Appendix C — Common problems & fixes

| Problem | Wajah | Fix |
|---|---|---|
| SSL AutoSSL fail | Domain abhi purane IP par resolve ho raha hai | Propagation wait (TTL 300 → ~30 min), dobara run |
| Site "not private" warning | Cert abhi issue nahi hua | 10–45 min wait; window raat mein rakho |
| Images nahi dik rahi | Cloudinary keys missing | `.env` mein `CLOUDINARY_*` check + `php artisan config:cache` |
| Email nahi ja rahi | SMTP galat / `MAIL_MAILER=log` | Zoho Settings enable, `smtp.zoho.in:587` TLS; `mail-tester.com` se test |
| Email spam mein ja rahi | SPF me naya IP nahi / PTR nahi | SPF update + PTR set karwao; ya Zoho SMTP use karo |
| Admin login loop | `SESSION_DOMAIN` set kar diya | `SESSION_DOMAIN=null` rakh do (dot se shuru hone wali value nahi) |
| Purani URL 404 | Redirect map mein nahi | `config/redirects.php` → `exact` mein add + `config:cache` |
| Redirect loop | `.htaccess` HTTPS rule + proxy conflict | `https` condition ko `%{HTTP:X-Forwarded-Proto}` se replace karo (agar Cloudflare/CDN ho) |
| `sitemap.xml` 404 | `route:cache` purana | `php artisan optimize:clear && php artisan route:cache` |
| Old site kuch users ko abhi bhi dikhti hai | DNS cache | TTL 300 tha to 30 min; warna 6 ghante. Browser cache bhi clear karo |
| `studentportal.mbalondon.org.uk` browser mein block | HSTS `includeSubDomains` | Student portal ke paas valid SSL hai (verified) — agar phir bhi issue ho to `.htaccess` se `includeSubDomains` hata do |

---

## Appendix D — Is branch mein maine kya code change kiya

| File | Kya karta hai |
|---|---|
| `config/redirects.php` | **Naya.** Purane WordPress URLs ka map: `exact` (1:1), `smart` (slug DB lookup → sahi naya page), `patterns` (regex catch-all: `/wp-*`, `/shop`, `/product/*`, feeds, archives). |
| `app/Http/Middleware/LegacyRedirects.php` | **Naya.** Har GET request par (routing se pehle) map check karta hai, match hone par 301. Query string preserve. DB error aaye to chupchap skip + log. |
| `bootstrap/app.php` | Upar wala middleware `web` group mein register kiya. |
| `app/Http/Controllers/SitemapController.php` | **Naya.** `/sitemap.xml` — 25 static pages + 68 programmes + blogs + news, `lastmod` ke saath. |
| `resources/views/sitemap/index.blade.php` | **Naya.** XML view. |
| `routes/web.php` | `/sitemap.xml` route (catch-all `/{slug}` se **pehle**). |
| `public/robots.txt` | `Sitemap:` line + `/admin`, `/livewire`, `/storage` ko crawl se block. |
| `.env.example` | `LEGACY_REDIRECTS_ENABLED=true` (emergency kill-switch). |

**Kill switch:** `.env` mein `LEGACY_REDIRECTS_ENABLED=false` → `php artisan config:cache`
→ saare purane redirects band, baaki site jaise ki thi.

**Rules edit kaise karein:** `config/redirects.php` kholo → `exact` array mein line add karo →
```bash
php artisan config:cache
```
⚠️ `exact` mein kabhi bhi koi aisi path mat daalna jo naye site ka real page ho
(`about-us`, `programs`, `contact`, `blogs`...) — warna woh page redirect ho jayega.
