# `mbalondon.org.uk` 404 fix — MilesWeb cPanel (addon domain → same Laravel app)

Situation: `demo.vsinfosys.in` pe Laravel app chal raha hai. `mbalondon.org.uk` cPanel me add kiya,
"previous folder wala path" diya → **404**.

Short answer: **aapka step adhura/galat hai.** Is Laravel app ka document root **`public/`** folder hai,
repo root nahi. Naye domain ka path bhi usi `public/` pe point hona chahiye (ya us folder pe jahan
demo ka docroot actually hai). Repo-root pe point karne se Apache/LiteSpeed ko koi `index.php`/`index.html`
nahi milta → 404 (ya kabhi 403).

---

## Step 0 — Pehle confirm karo 404 kahan se aa raha hai (2 minute diagnosis)

Browser me `http://mbalondon.org.uk` kholo aur page ka **look** dekho:

| Jo dikhe | Matlab | Kahan jump karo |
|----------|--------|-----------------|
| Plain Apache/LiteSpeed "404 Not Found" ya cPanel default error page | Docroot galat hai / files nahi hain | **Step 1** |
| Aapka app-style 404 page (repo ka `resources/views/errors/404.blade.php`, "Back to Home" button) | Docroot sahi hai, Laravel chal raha hai — route/asset issue | **Step 4** |
| Domain "parked"/"for sale"/registrar page | DNS abhi MilesWeb pe point nahi hua | **Step 2** |

Terminal/cPanel se exact server bhi check kar sakte ho (sandbox me DNS allow nahi, aap apne machine pe chalao):

```bash
curl -I http://mbalondon.org.uk/
# Server: LiteSpeed/Apache + 404  → docroot problem (Step 1)
```

---

## Step 1 — Document Root ko `public/` pe set karo (asli fix)

### 1a. Pehle dekho demo domain ka docroot kya hai
cPanel → **Domains** (ya **Subdomains**) → `demo.vsinfosys.in` ke saamne **Document Root** column note karo.
Zyadatar ye hoga:

```
/home/USER/demo.vsinfosys.in/public
```

(Repo docs is folder ko `~/demo.vsinfosys.in` maante hain; `public/` uske andar hai.)

### 1b. Ab `mbalondon.org.uk` ka docroot same `public/` pe karo
cPanel → **Domains** → `mbalondon.org.uk` ke saamne **Manage** / pencil (edit) icon →
**Document Root** badal kar:

```
/home/USER/demo.vsinfosys.in/public
```

Save. cPanel LiteSpeed/Apache config rebuild karta hai — 1–5 minute lag sakte hain.

> Dono domains ek hi `public/` share kar sakte hain. Ek hi codebase + ek hi `.env` + ek hi DB
> dono domains pe same site dega. Laravel domain-agnostic hai (routes me koi `->domain()` nahi),
> isliye ye kaam karta hai.

### 1c. Agar aapne naye domain ke liye ALAG folder banaya tha
Yaani path diya tha `mbalondon.org.uk` (khali naya folder) — to usme **code hai hi nahi**, isliye 404.
Do options:

- **Same site chahiye** → 1b follow karo, docroot `demo.vsinfosys.in/public` pe point kar do. Naya khali folder delete kar sakte ho.
- **Alag site/content chahiye** → us folder me poora app **dobara deploy** karo (git clone + composer install +
  alag `.env` + alag DB + `bash scripts/shared-hosting-optimize.sh`), phir docroot `mbalondon.org.uk/public` rakho.
  (Do alag sites ek hi docroot se nahi chal sakti.)

---

## Step 2 — DNS check karo

`mbalondon.org.uk` ke **nameservers MilesWeb ke hone chahiye** (cPanel → Domains me domain add karte waqt
DNS zone apne aap ban jaata hai, lekin registrar side NS point karna zaroori hai):

```bash
dig +short NS mbalondon.org.uk
# MilesWeb ke NS (jaise ns1.milesweb.in / ns2.milesweb.in ...) aane chahiye
```

Agar NS abhi purane host/registrar ke hain → domain yahin resolve hoga jahan pehle tha, naye docroot pe nahi.
NS change ko propagate hone me kuch ghante lag sakte hain.

---

## Step 3 — SSL (HTTPS)

cPanel → **SSL/TLS Status** → `mbalondon.org.uk` + `www.mbalondon.org.uk` select → **Run AutoSSL**.
Green lock aane ke baad hi site HTTPS pe sahi chalegi. `public/.htaccess` me HSTS header already set hai.

---

## Step 4 — Agar docroot sahi hai phir bhi 404 (Laravel side)

Docroot `public/` hai aur phir bhi issue ho to server pe SSH/cPanel Terminal me:

```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
cd /home/USER/demo.vsinfosys.in

# 1) Cache rebuild (docroot/env change ke baad zaroori)
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2) Health check route
curl -I https://mbalondon.org.uk/up     # 200 aana chahiye

# 3) Permissions (public + storage writable)
ls -la public/index.php
```

`.htaccess`/rewrite na chalne ki surat me host se **`mod_rewrite` + `AllowOverride All`** confirm karwao
(LiteSpeed me `.htaccess` by default chalta hai).

---

## Step 5 — Production domain ke liye `.env` + canonical (recommended)

Ab site live domain pe hai, to `.env` me:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mbalondon.org.uk
```

`.env` badalne ke baad **cache rebuild** karo (`config:cache`), warna purani value chipki rahegi:

```bash
php artisan optimize:clear && php artisan config:cache
```

> Note: `APP_URL` ek hi value hoti hai aur dono domains same `.env` share karte hain.
> App zyadatar relative URLs + Cloudinary use karta hai, isliye `url('/')` jaisi jagah
> (404/500 page ka "Back to Home") current request host use karta hai — problem nahi hoti.
> Canonical domain ek rakhna SEO ke liye better hai.

### Canonical / redirect (SEO)
Do domains pe same content = duplicate content. Ek ko primary banao (e.g. `mbalondon.org.uk`),
dusre (`demo.vsinfosys.in`) se 301 redirect kar do — cPanel → **Redirects**, ya `public/.htaccess` me:

```apache
# demo → production redirect (sirf tab jab demo ko staging se hatana ho)
RewriteCond %{HTTP_HOST} ^demo\.vsinfosys\.in$ [NC]
RewriteRule ^(.*)$ https://mbalondon.org.uk/$1 [L,R=301]
```

(Demo ko staging ke taur pe zinda rakhna ho to redirect mat lagao.)

---

## Quick checklist

- [ ] Docroot = `.../demo.vsinfosys.in/public` (repo root NAHI)
- [ ] `mbalondon.org.uk` NS MilesWeb pe point + DNS propagate
- [ ] AutoSSL run → HTTPS green
- [ ] `php artisan optimize:clear && php artisan config:cache`
- [ ] `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://mbalondon.org.uk`
- [ ] `curl -I https://mbalondon.org.uk/up` → 200
- [ ] Homepage + admin login + ek Cloudinary image HTTPS pe load
- [ ] Canonical decide (redirect ya dono zinda)

Sabse common galti: **path repo-root pe set karna, `public/` pe nahi.** Wahin aapka 404 aa raha hai.
