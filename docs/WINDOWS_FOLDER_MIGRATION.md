# Purane PC se raw folder copy karke naye laptop pe chalana

Ye guide tab hai jab tum **GitHub se fresh clone nahi** kar rahe, balki purane Windows PC ka **poora project folder** (`vendor/`, `.env`, `public/`, sab kuch) copy karke naye laptop ke Laragon me daal rahe ho.

**Goal:** naya laptop bilkul waise hi behave kare jaise purana PC karta tha.

Fresh clone wala raasta chahiye to: [`WINDOWS_LARAGON_SETUP.md`](WINDOWS_LARAGON_SETUP.md)

---

## Kyun ye raasta aksar better hai

| | Fresh clone | Folder copy |
|---|---|---|
| `vendor/` package versions | Composer dobara resolve karega (minor drift possible) | **Byte-identical** |
| `.env` + `APP_KEY` | naya banana padega | **Jaise ka taisa** |
| Internet | chahiye (GitHub + Packagist + npm) | **Sirf DB dump transfer** |
| Uncommitted local changes | **Chale jayenge** | Preserve ho jayenge |
| Risk | version drift | purane PC ke **stale caches** (neeche fix hai) |

---

## ⚠️ Ek baat pehle samajh lo — "pehle chal raha tha" ka trap

Purane PC pe agar `bootstrap/cache/config.php` maujood hai, to Laravel `config/*.php` files **padhta hi nahi** — wo cached array use karta hai.

Iska matlab:

- Us cached file me purane PC ke **absolute paths** (`C:\laragon\www\...`) aur purani DB credentials baked hain. Naye laptop pe path alag hua to app ajeeb tareeke se tootegi.
- Aur agar purane PC ka PHP **8.3** hai, to app sirf isi cache ki wajah se chal rahi hai — kyunki `config/database.php` me `Pdo\Mysql` (PHP 8.4+ only) hai. Cache clear karte hi `Class "Pdo\Mysql" not found` aa jayega.

Isliye **Phase 0 ka diagnostic skip mat karna.** Wo 30 second me bata dega ki PHP 8.4 lena hai ya 8.3 se kaam chal jayega.

---

## Phase 0 — Purane PC pe (ye sab karne ke baad hi usse haath lagana band karna)

### 0a. Facts collect karo

Purane PC ke project folder me terminal kholo:

```bash
php -v
php -r "echo 'PHP ', PHP_VERSION, PHP_EOL, (class_exists('Pdo\Mysql') ? 'Pdo\Mysql: YES -> PHP 8.4+ chahiye' : 'Pdo\Mysql: NO -> PHP 8.3 ya purana'), PHP_EOL;"
php -m
mysql -V
php artisan --version
php artisan about
```

Ye bhi note karo:

```bash
where php
type .env | findstr /B "APP_URL DB_"
dir bootstrap\cache
```

Ek text file me paste karke rakh lo — naye laptop pe reference ke kaam aayega.

> `dir bootstrap\cache` me `config.php` dikhe → upar wala trap applicable hai.

### 0b. 🔑 Laragon ka poora PHP folder copy kar lo (sabse strong move)

"Exactly same" chahiye to best tareeka: purane Laragon ka **PHP folder hi utha lo**.

```
C:\laragon\bin\php\php-8.x.y-Win32-vs17-x64\
```

Isme PHP binary + `php.ini` + saare enabled extensions ek saath aa jate hain. Naye laptop pe version, ini settings aur extension list **guess karne ki zarurat hi nahi rahegi.**

Folder ka exact naam note kar lo.

### 0c. DB dump nikalo

`.env` se `DB_DATABASE` ka naam dekho, phir:

```bash
mysqldump -u root --single-transaction --default-character-set=utf8mb4 --routines --events --add-drop-table maverick_db > C:\transfer\maverick_db.sql
```

(`maverick_db` ki jagah tumhara asli DB naam. Password set ho to `-p` add karo.)

Verify — file khaali na ho:

```bash
dir C:\transfer\maverick_db.sql
```

### 0d. Project folder copy karo

`node_modules` chhod do (bada hai aur chahiye hi nahi — [niche dekho](#npm-ki-zarurat-nahi)):

```bash
robocopy "C:\laragon\www\maverick" "D:\transfer\maverick" /E /COPY:DAT /R:1 /W:1 /XD node_modules
```

> `/XD node_modules` sirf size bachane ke liye hai. `vendor/`, `.env`, `public/` **zaroor** aane chahiye.
> ZIP banana ho to 7-Zip use karo — Windows ka built-in "Send to > Compressed folder" kabhi-kabhi hidden files (jaise `.env`) chhod deta hai.

---

## Phase 1 — Naye laptop pe folder rakho

### 1a. Wahi path use karo jo purane PC pe tha

```
C:\laragon\www\maverick
```

Same path = zero absolute-path surprises. Agar purane PC pe path alag tha, to koi baat nahi — Phase 4 me `composer dump-autoload` usse handle kar lega.

```bash
robocopy "D:\transfer\maverick" "C:\laragon\www\maverick" /E /COPY:DAT /R:1 /W:1
```

### 1b. ❌ Folder OneDrive/Desktop me mat rakho

OneDrive sync `storage/` aur `bootstrap/cache/` ko lock kar deta hai → random "could not be opened" errors. `C:\laragon\www\` hi best hai.

Windows Defender me `C:\laragon` ko exclusion me daal do — PHP 5x fast ho jayega.

---

## Phase 2 — PHP match karo (Laragon me)

### Agar tumne Phase 0b me PHP folder copy kiya tha (recommended)

1. Wo poora folder yahan paste karo:
   ```
   C:\laragon\bin\php\php-8.x.y-Win32-vs17-x64\
   ```
2. **VC++ Redistributable** install karo (naye laptop pe aksar missing hota hai):
   👉 https://aka.ms/vs/17/release/vc_redist.x64.exe → install → reboot
3. Agar `php.ini` me koi **absolute path** likha ho (jaise `extension_dir`, `curl.cainfo`, `error_log`), to use naye folder ke hisaab se theek kar do.
4. Laragon → right-click → **PHP → Version → wahi folder** → **Stop All** → **Start All**

### Agar PHP folder copy nahi kiya

[`WINDOWS_LARAGON_SETUP.md` Step 1](WINDOWS_LARAGON_SETUP.md) follow karo, aur **wahi version** lo jo Phase 0a me mila.

### Verify

```bash
php -v
php -r "echo class_exists('Pdo\Mysql') ? 'Pdo\Mysql OK' : 'Pdo\Mysql MISSING (PHP < 8.4)';"
```

Agar `MISSING` aaye **aur** tum config cache delete karne wale ho (Phase 3 me karna hi hai), to **PHP 8.4 lagao** — warna app boot nahi hogi.

---

## Phase 3 — 🔴 Stale caches saaf karo (ye step sabse important hai)

In files me purane PC ke absolute paths baked hain. Inhe delete kiye bina app ya to chalegi hi nahi, ya aise bugs degi jinka koi sar-pair nahi hoga.

Laragon Terminal / cmd me project root se:

```bat
del /Q bootstrap\cache\*.php
del /Q public\hot
del /S /Q storage\framework\views\*.php
del /S /Q storage\framework\cache\data\*
del /S /Q storage\framework\sessions\*
del /Q storage\logs\*.log
```

PowerShell me:

```powershell
Remove-Item bootstrap\cache\*.php, public\hot -Force -ErrorAction SilentlyContinue
Remove-Item storage\framework\views\*, storage\framework\cache\data\*, storage\framework\sessions\* -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item storage\logs\*.log -Force -ErrorAction SilentlyContinue
```

Kya kya delete ho raha hai aur kyun:

| File/folder | Kyun delete |
|---|---|
| `bootstrap/cache/config.php` | Purane PC ki **poori config + DB credentials + paths** isme frozen hain |
| `bootstrap/cache/packages.php`, `services.php` | Package discovery me **absolute vendor paths** hote hain |
| `bootstrap/cache/routes-v7.php`, `events.php` | Route/event cache, stale |
| `public/hot` | Purane PC pe `npm run dev` chala tha to ye bacha hoga → naya laptop `localhost:5173` se assets maangega aur site bina CSS ke dikhegi |
| `storage/framework/views/*.php` | Compiled Blade, purane paths ke saath |
| `storage/framework/cache/data/*` | File cache (settings cache bhi yahin) |
| `storage/framework/sessions/*` | Purane PC ke login sessions |

> ⚠️ In folders ke andar jo `.gitignore` files hain unhe **mat** delete karna — upar ke commands unhe chhodte hain.

> ✅ `vendor/` ko **mat** delete karna — wahi to iss raaste ka poora faayda hai.
> ✅ `public/assets`, `public/css`, `public/js` ko bhi mat chhedna — asli frontend wahi hai.

---

## Phase 4 — Database

### 4a. Wahi DB naam banao jo `.env` me likha hai

```bash
type .env | findstr DB_
```

Maan lo `DB_DATABASE=maverick_db`:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS maverick_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 4b. Dump import karo

**cmd / Laragon Terminal:**
```bash
mysql -u root --default-character-set=utf8mb4 maverick_db < C:\transfer\maverick_db.sql
```

**PowerShell** (`<` redirect support nahi karta):
```powershell
cmd /c "mysql -u root --default-character-set=utf8mb4 maverick_db < C:\transfer\maverick_db.sql"
```

Bada dump: `--max_allowed_packet=512M` add kar do.

### 4c. MySQL flavour check

```bash
mysql -V
```

Purane PC pe MySQL 8 tha aur naye Laragon me MariaDB hai (ya ulta) → import me errors aa sakte hain. Aise me Laragon → **Menu → MySQL → Version** se wahi flavour select karo jo purane PC pe tha.

### 4d. Verify

```bash
mysql -u root -e "USE maverick_db; SELECT COUNT(*) AS settings_rows FROM settings; SELECT COUNT(*) AS users FROM users;"
```

`settings` table me hazaron rows honi chahiye — site ka content wahin hai.

---

## Phase 5 — `.env` review (minimal touch)

`.env` folder ke saath aa chuki hai. **Usse jitna kam chhedo utna accha.**

```bash
code .env
```

| Key | Kya karna |
|---|---|
| `APP_KEY` | 🔴 **Bilkul mat badlo. `php artisan key:generate` kabhi mat chalao.** Encrypted settings (Zoho/Zapier credentials) permanently unreadable ho jayenge |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Naye Laragon ke hisaab se (Laragon root = password khaali) |
| `DB_PORT` | Laragon me 3307 kiya ho to update karo |
| `APP_URL` | Wahi rakho jo purane PC pe tha (jaise `http://maverick.test`) — tabhi same vhost banega |
| `CLOUDINARY_*` | Already bhari hongi — haath mat lagao |
| Baaki sab | Chhod do |

---

## Phase 6 — Laravel ko naye ghar se parichit karao

```bash
cd C:\laragon\www\maverick

composer dump-autoload
php artisan optimize:clear
php artisan migrate:status
php artisan about
```

- **`composer dump-autoload`** — `vendor/composer/autoload_*.php` ko naye path ke hisaab se regenerate karta hai. Path same ho to bhi chala lo, 2 second lagta hai, nuksan zero.
- **`migrate:status`** — sab `Ran` dikhna chahiye. Koi `Pending` ho to `php artisan migrate` chala do.
- **`php artisan about`** — PHP version, DB connection, cache drivers ek jagah dikha deta hai. Purane PC wale output se compare kar lo.

> Yahan `Class "Pdo\Mysql" not found` aaye → PHP 8.3 chal raha hai. Phase 2 pe wapas jao aur 8.4 lagao.

### ❌ Ye commands bilkul mat chalana

| Command | Kyun nahi |
|---|---|
| `php artisan key:generate` | Encrypted data hamesha ke liye kharab |
| `composer update` | Version drift — "exactly same" ka poora maqsad khatam |
| `php artisan migrate:fresh` / `migrate:refresh` | **Saara data uda dega** |
| `php artisan db:seed` | Import kiye hue content ke upar duplicates |
| `php artisan config:cache` | Local dev me zarurat nahi, aur purani galti dohrayega |

---

## Phase 7 — Virtual host aur launch

1. Laragon → right-click → **Reload** (admin privileges ke saath chal raha ho)
2. `http://maverick.test` — folder ka naam `maverick` hai to Laragon yahi vhost banata hai
3. Check karo:
   - http://maverick.test → homepage, content ke saath
   - http://maverick.test/up → `200`
   - http://maverick.test/admin → Filament login

**Login:** purane PC wala admin user DB dump me aa chuka hai — wahi email/password chalega.
Password yaad na ho:

```bash
php artisan admin:create --email=wahi@email.com --password=NayaPassword123
```

---

## <a id="npm-ki-zarurat-nahi"></a>npm ki zarurat nahi hai

`resources/views/` me kahin bhi `@vite` use nahi hota. Frontend ka saara CSS/JS `public/assets/`, `public/css/`, `public/js/` me plain committed files hain, aur Filament ke assets `vendor/` ke saath aa chuke hain.

To `node_modules` copy na karna, aur `npm install` chalane ki zarurat **nahi**.

Sirf tab chahiye jab `resources/css` / `resources/js` pe kaam karo — tab [Setup guide ka Step 4 + 13](WINDOWS_LARAGON_SETUP.md) dekh lena.

---

## Automation

Phase 1 (folder copy) ke baad Phase 3–6 ek command me:

```powershell
cd C:\laragon\www\maverick
powershell -ExecutionPolicy Bypass -File scripts\windows-folder-migrate.ps1 -SqlDump "C:\transfer\maverick_db.sql"
```

Script:
- `.env` aur `APP_KEY` ko **chhuta tak nahi** (sirf verify karta hai)
- PHP vs `Pdo\Mysql` ka check karta hai aur fail hone pe clearly batata hai
- saare stale caches clear karta hai
- DB banata + dump import karta hai
- `composer dump-autoload`, `optimize:clear`, `migrate:status` chalata hai
- final health report deta hai

Dump pehle hi import kar chuke ho to `-SqlDump` chhod do.
Dry run: `-WhatIfOnly` — sirf batayega kya karega, karega kuch nahi.

---

## Troubleshooting

| Symptom | Wajah / Fix |
|---|---|
| `Class "Pdo\Mysql" not found` | PHP 8.3 chal raha hai aur ab config cached nahi hai → PHP 8.4 lagao (Phase 2) |
| Site load hoti hai par **bina CSS** ke | `public/hot` delete nahi kiya (Phase 3) |
| `No such file or directory ... C:\Users\old-pc\...` | `bootstrap/cache/*.php` ya compiled views purane PC ke → Phase 3 dobara |
| `The stream or file "storage/logs/laravel.log" could not be opened` | Folder OneDrive me hai, ya antivirus block kar raha → `C:\laragon\www` me rakho + Defender exclusion |
| `Class 'App\...' not found` | `composer dump-autoload` chalao |
| Login nahi ho raha (credentials sahi hain) | `storage/framework/sessions/*` clear karo, browser cookies bhi |
| Admin me "Unable to decrypt" / Zoho settings blank | `APP_KEY` badal gayi hai. Purane PC ki `.env` se asli `APP_KEY` wapas paste karo |
| `migrate:status` me sab `Pending` dikhe | Galat DB se connected ho — `.env` ka `DB_DATABASE` check karo |
| `maverick.test` nahi khulta | Laragon **Run as Administrator** → Reload; ya `hosts` file me `127.0.0.1 maverick.test` |
| MySQL import errors (`Unknown collation`, syntax) | MySQL vs MariaDB mismatch → Phase 4c |

---

## Final checklist

- [ ] Phase 0 diagnostic purane PC pe chala kar output save kiya
- [ ] PHP version match (ya 8.4) + VC++ Redistributable installed
- [ ] Folder `C:\laragon\www\maverick` me hai (OneDrive me nahi)
- [ ] `vendor/` copy hua hai aur delete nahi kiya
- [ ] `bootstrap/cache/*.php`, `public/hot`, compiled views clear kiye
- [ ] DB banayi + dump import hua, `settings` table me rows hain
- [ ] `.env` ka `APP_KEY` **bilkul waise ka waisa** hai
- [ ] `composer dump-autoload` + `optimize:clear` chalaya
- [ ] `php artisan migrate:status` → sab `Ran`
- [ ] `/up` → 200, homepage + `/admin` dono chal rahe hain
