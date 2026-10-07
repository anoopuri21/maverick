# Maverick — Naye Windows Laptop pe Setup (Laragon)

Ye guide bilkul zero se hai. Assume kiya gaya hai ki laptop pe abhi sirf ye installed hai:

- VS Code
- Laragon
- Node 22
- nvm (nvm-windows)

Project stack: **Laravel 13.20 + Filament 3.3 + Livewire 3 + Tailwind 4 + Vite 8**, DB = **MySQL** (Laragon wala), media = **Cloudinary**.

---

## ⚠️ Shuru karne se pehle — 4 baatein jo 90% time waste bachati hain

### 1. PHP **8.4+** mandatory hai. 8.3 pe app boot hi nahi hogi.

`config/database.php` me Laravel 13 skeleton `Pdo\Mysql::ATTR_SSL_CA` use karta hai. `Pdo\Mysql` class **PHP 8.4** me aayi thi.

Ye line `extension_loaded('pdo_mysql')` ke andar hai, aur Laravel **har request pe saari config files load karta hai** — to PHP 8.3 pe `pdo_mysql` enabled hote hi poora app boot pe crash karega:

```
Error: Class "Pdo\Mysql" not found in config/database.php
```

Ye sirf MySQL ka issue nahi hai — `php artisan` commands aur SQLite pe bhi yahi fatal error aayega. `composer.json` me `php: ^8.3` likha hai, **wo galat/outdated hai — ignore karo, 8.4 hi lagao.**

### 2. Laragon ka Terminal apna bundled Node/PHP use karta hai

Laragon Full ke andar `C:\laragon\bin\nodejs` hota hai. Laragon Terminal me `node -v` wahi purana Node dikhayega, nvm wala **nahi**. Fix Step 4 me hai.

### 3. VC++ Redistributable ke bina PHP 8.4 chalega hi nahi

Naye laptop pe aksar missing hota hai → `VCRUNTIME140.dll was not found`. Step 1 me link hai.

### 4. Site ka content `database/settings/` me hai, seeders me nahi

Is project me ~137 Spatie Settings classes hain aur **93 settings-migrations** `database/settings/` me. Homepage/MBA landing ka 90% content wahan se aata hai, aur wo `php artisan migrate` ke saath apne aap chalti hain. Isliye **`migrate` skip karke sirf seed karoge to site khaali dikhegi.**

---

## Step 0 — Jo already hai, verify karo

Laragon kholo → right-click → **Terminal**.

```bash
php -v
composer -V
git --version
node -v
npm -v
nvm version
```

> **Laragon Full vs Lite:** Full edition me Git, Composer, Node, HeidiSQL bundled aate hain. Check karne ke liye `C:\laragon\bin\` folder kholo.

---

## Step 1 — PHP 8.4 install karo (Laragon ke andar) 🔴 Mandatory

### 1a. Visual C++ Redistributable (pehle ye)

👉 **Link:** https://aka.ms/vs/17/release/vc_redist.x64.exe

Install karo aur **reboot** kar lo. (PHP 8.4 Windows builds VS17 se compile hote hain — ye runtime chahiye hi chahiye.)

### 1b. PHP 8.4 download

👉 **Link:** https://windows.php.net/download#php-8.4

Page pe **PHP 8.4 (x.y.z)** section me se ye wala:

> **VS17 x64 Thread Safe** → `php-8.4.x-Win32-vs17-x64.zip`

❗ **Thread Safe** hi chahiye (Non-Thread-Safe me `php8apache2_4.dll` nahi hota, Apache load nahi kar paayega). 32-bit laptop ho to x86.

### 1c. Laragon me rakho

1. ZIP extract karo.
2. Folder yahan rakho, naam exactly aisa:
   ```
   C:\laragon\bin\php\php-8.4.x-Win32-vs17-x64\
   ```
3. Us folder me `php.ini-development` ko copy karke **`php.ini`** naam do.

### 1d. php.ini configure karo

`C:\laragon\bin\php\php-8.4.x-Win32-vs17-x64\php.ini` VS Code me kholo.

**extension_dir — absolute path do** (relative `"ext"` CLI me kabhi-kabhi resolve nahi hota):

```ini
extension_dir = "C:\laragon\bin\php\php-8.4.x-Win32-vs17-x64\ext"
```

Phir in lines ka `;` hatao (`Ctrl+F` → `;extension=`):

```ini
extension=bcmath
extension=curl
extension=exif
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=mysqli
extension=pdo_sqlite
extension=sqlite3
extension=sodium
extension=zip
```

Aur ye values set karo:

```ini
memory_limit = 512M
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
date.timezone = Asia/Kolkata

[opcache]
opcache.enable = 1
opcache.enable_cli = 0
opcache.revalidate_freq = 0
```

**Kaunsa extension kyun (is project ke liye):**

| Extension | Kiske liye |
|---|---|
| `intl`, `mbstring` | Filament, Carbon, spatie/laravel-data |
| `gd`, `exif` | Image handling / Filament uploads |
| `curl`, `openssl` | Cloudinary SDK, Composer |
| `zip` | Composer package extraction |
| `pdo_mysql`, `mysqli` | Laragon MySQL |
| `pdo_sqlite`, `sqlite3` | `php artisan test` — `phpunit.xml` me `DB_CONNECTION=sqlite`, `:memory:` |
| `sodium` | Encryption |

> `dom`, `tokenizer`, `ctype`, `json`, `filter`, `libxml`, `simplexml` Windows PHP me **built-in** hain — inhe php.ini me dhoondne ki zarurat nahi.

### 1e. Laragon me PHP 8.4 select karo

Laragon → right-click → **PHP → Version → php-8.4.x-Win32-vs17-x64** → phir **Stop All** → **Start All**.

Verify:

```bash
php -v          # PHP 8.4.x
php -m          # intl, gd, curl, zip, pdo_mysql, sqlite3, mbstring dikhne chahiye
php -r "echo class_exists('Pdo\Mysql') ? 'PDO OK' : 'TOO OLD';"
```

Last command **`PDO OK`** bole, tabhi aage badho.

> **Apache start na ho to:** purane Laragon ka Apache VS16 build hota hai. Mostly VS17 PHP ke saath chal jata hai, par na chale to —
> - Laragon → **Menu → Apache → Version** me koi naya VS17 Apache select karo, ya
> - Apache Lounge (https://www.apachelounge.com/download/) se Apache 2.4 **VS17 x64** le kar `C:\laragon\bin\apache\` me daalo, ya
> - Short-term workaround: Apache chhodo aur `php artisan serve` use karo (Step 14).

---

## Step 2 — Composer install

Agar `composer -V` kaam nahi kar raha:

👉 **Link:** https://getcomposer.org/Composer-Setup.exe

- Jab PHP path pooche → **`C:\laragon\bin\php\php-8.4.x-Win32-vs17-x64\php.exe`** (ye step sabse important hai — galat PHP select kiya to `composer install` platform error dega).
- "Add to PATH" tick rehne do. Proxy page skip.

Verify (**naya** terminal kholkar):

```bash
composer -V
composer diagnose          # PHP version bhi print karta hai
```

Optional, lambe installs ke liye:

```bash
composer config -g process-timeout 2000
```

---

## Step 3 — Git install

Agar `git --version` kaam nahi kar raha:

👉 **Link:** https://git-scm.com/download/win → "64-bit Git for Windows Setup"

Installer choices:
- Editor: **Use Visual Studio Code**
- Default branch: `main`
- PATH: **Git from the command line and also from 3rd-party software**
- Line endings: **Checkout as-is, commit Unix-style line endings** (repo ka `.gitattributes` `eol=lf` enforce karta hai)
- Credential helper: **Git Credential Manager**

Install ke baad:

```bash
git --version
git config --global user.name "Tumhara Naam"
git config --global user.email "tum@example.com"
git config --global core.autocrlf false
git config --global core.longpaths true
```

> `core.longpaths true` zaroori hai — `vendor/` me Filament/Livewire ke nested paths Windows ki 260-char limit cross kar jate hain, warna `Filename too long` error aata hai.

---

## Step 4 — Node version theek karo (Laragon wala trap)

Vite 8 ko Node `^20.19.0 || >=22.12.0` chahiye.

**Pehle check karo kaunsa node chal raha hai:**

```bash
node -v
where node
```

Agar `where node` me `C:\laragon\bin\nodejs\...` dikh raha hai, to Laragon ka bundled Node use ho raha hai, nvm wala nahi.

**Fix (koi ek):**

- **Option A (recommended):** Laragon ka bundled Node hata do —
  `C:\laragon\bin\nodejs` folder ko rename karke `nodejs_disabled` kar do, phir Laragon restart.
- **Option B:** npm commands ke liye Laragon Terminal ki jagah **Windows Terminal / PowerShell** use karo (wahan nvm wala Node milega). PHP/artisan ke liye Laragon Terminal, npm ke liye normal terminal.

Phir:

```bash
nvm install 22.20.0
nvm use 22.20.0
node -v     # v22.20.0 (>= 22.12 hona chahiye)
```

> `nvm use` ke liye terminal **Administrator** me chahiye hota hai (nvm-windows symlink banata hai).

---

## Step 5 — Optional tools

| Tool | Kyun | Link |
|---|---|---|
| **HeidiSQL** | DB GUI + SQL dump import. Laragon Full me already (`C:\laragon\bin\heidisql`) | https://www.heidisql.com/download.php |
| **DBeaver CE** | Better cross-DB GUI | https://dbeaver.io/download/ |
| **Windows Terminal** | Laragon/cmd se behtar | Microsoft Store |

### VS Code extensions

```bash
code --install-extension bmewburn.vscode-intelephense-client
code --install-extension laravel.vscode-laravel
code --install-extension onecentlin.laravel-blade
code --install-extension amiralizadeh9480.laravel-extra-intellisense
code --install-extension bradlc.vscode-tailwindcss
code --install-extension mikestead.dotenv
code --install-extension EditorConfig.EditorConfig
code --install-extension xdebug.php-debug
```

---

## Step 6 — Project clone

Laragon ka web root `C:\laragon\www` hai — yahin clone karo taaki auto virtual host bane.

```bash
cd C:\laragon\www
git clone https://github.com/anoopuri21/maverick.git
cd maverick
```

Private repo pe Git Credential Manager browser kholega — GitHub se login kar lena.

Dusri branch chahiye:

```bash
git clone -b main https://github.com/anoopuri21/maverick.git
# ya existing clone me:
git fetch origin && git checkout main
```

### Virtual host

Laragon → right-click → **Reload** (ya Stop All → Start All).

Laragon khud `http://maverick.test` banayega aur Laravel detect karke document root `maverick/public` pe set kar dega.

Na chale to:
- Laragon ko **Run as Administrator** se chalao (hosts file edit karne ko admin chahiye).
- Laragon → **Preferences → General → "Auto virtual hosts"** tick hona chahiye.
- `C:\laragon\etc\apache2\sites-enabled\auto.maverick.test.conf` me `DocumentRoot "C:/laragon/www/maverick/public"` check karo.

---

## Step 7 — PHP dependencies

```bash
cd C:\laragon\www\maverick
composer install
```

2–5 min lagenge. End me `package:discover` aur `filament:upgrade` chalenge (Filament apne CSS/JS `public/css/filament`, `public/js/filament` me publish karega).

> Yahan agar `Class "Pdo\Mysql" not found` aaye → Step 1 adhoora hai, PHP 8.3 chal raha hai.
> `Your requirements could not be resolved ... php ^8.3` aaye → Composer purane PHP se bandha hai, Composer-Setup dobara chalao.

---

## Step 8 — Laragon me Database banao

Laragon ka MySQL root user **bina password** ka hota hai.

### Option A — Terminal (fastest)

```bash
mysql -u root -e "CREATE DATABASE maverick_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "SHOW DATABASES;"
```

### Option B — HeidiSQL (GUI)

1. Laragon → **Database** button.
2. Session: Host `127.0.0.1`, User `root`, Password **khaali**, Port `3306` → **Open**.
3. Left panel → right-click → **Create new → Database** → Name `maverick_db`, Collation `utf8mb4_unicode_ci` → OK.

> **MySQL start nahi ho raha?** Port 3306 kisi purane MySQL/XAMPP service ne le rakha hoga. Laragon → **Preferences → Services & Ports** me MySQL port `3307` kar do, aur `.env` me `DB_PORT=3307`.

---

## Step 9 — `.env` banao

```bash
copy .env.example .env
```
> Git Bash use kar rahe ho to `cp .env.example .env`.

`code .env` karke ye values set karo:

```dotenv
APP_NAME=Maverick
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://maverick.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maverick_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

MAIL_MAILER=log

# Cloudinary — team se real keys lo
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_UPLOAD_FOLDER=maverick-academy
```

❗ `.env.example` me default `DB_CONNECTION=sqlite` hai — **`mysql`** karna zaroori hai.

📌 **Cloudinary keys** repo me nahi hain (gitignored). Inke bina app chalega, par admin me image upload aur Cloudinary-hosted images fail karengi. Team lead se maang lo.

> Apache non-standard port pe ho (e.g. 8080) to `APP_URL=http://maverick.test:8080` likhna — warna Vite/Livewire ke asset URLs galat banenge.

App key:

```bash
php artisan key:generate
```

---

## Step 10 — Database schema + content

**Dhyan do:** `php artisan migrate` do cheezein chalata hai —
1. `database/migrations/` → 70+ schema migrations (tables)
2. `database/settings/` → **93 Spatie settings migrations** (homepage, MBA landing, SEO, CEO quote... ka actual text content)

Isliye `migrate` kabhi skip mat karna.

`database/seeders/` sirf Programs, Awards, Faculty Insights, Testimonials jaise catalog records daalta hai.

### Raasta A — Fresh DB (naye developer ke liye)

```bash
php artisan migrate --seed
```

- Pehli baar 1–3 min lag sakte hain (93 settings migrations + seeders).
- `TestimonialSeeder` testimonial images ko `public/assets/images/testimonials/` me dhoondta hai — wo repo me committed hain, to internet ki zarurat nahi. Na milein to wo Google se download try karega aur fail hone pe silently skip kar dega (crash nahi hoga).

Verify:

```bash
php artisan migrate:status
php artisan about
mysql -u root -e "USE maverick_db; SELECT COUNT(*) FROM settings; SHOW TABLES;"
```

`settings` table me hazaron rows hone chahiye.

### Raasta B — Production/staging SQL dump import

Agar `.sql` file di gayi hai:

**B1. Khaali DB** (ya reset):

```bash
mysql -u root -e "DROP DATABASE IF EXISTS maverick_db; CREATE DATABASE maverick_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**B2. Import**

**cmd / Laragon Terminal me:**
```bash
mysql -u root --default-character-set=utf8mb4 maverick_db < C:\Users\You\Downloads\maverick_dump.sql
```

**PowerShell me** (`<` redirect PowerShell me nahi chalta):
```powershell
cmd /c "mysql -u root --default-character-set=utf8mb4 maverick_db < C:\Users\You\Downloads\maverick_dump.sql"
```

Bada dump (>50 MB):
```bash
mysql -u root --max_allowed_packet=512M --default-character-set=utf8mb4 maverick_db < dump.sql
```

**HeidiSQL se:** DB select → **File → Run SQL file...** → encoding `UTF-8` → Run.

**B3. Pending migrations**

```bash
php artisan migrate
php artisan migrate:status
```

**B4. 🔴 APP_KEY ka rule**

Dump me encrypted settings ho sakti hain. Agar tumhe dump ke saath **purana `APP_KEY` diya gaya hai**, to `.env` me wahi paste karo aur **`php artisan key:generate` mat chalao** — warna wo data decrypt nahi hoga.

**B5.** Dump purane domain ka hai to admin se absolute URLs update kar lena. `.env` ka `APP_URL` already `http://maverick.test` hai.

---

## Step 11 — Admin user banao

Admin panel `/admin` pe hai aur sirf `is_admin = true` walon ko ghusne deta hai (`User::canAccessPanel`). Fresh DB me koi admin nahi hota.

**Interactive (recommended — password terminal history me nahi jata):**

```bash
php artisan admin:create
```

Email poochega (default `admin@maverick.test`) aur phir hidden password input lega.

**Non-interactive (scripts ke liye):**

```bash
php artisan admin:create --email=admin@maverick.test --password=Password@123
```

- Password minimum 8 characters.
- Email already exist karta ho to wo user **admin bana diya jayega** (naam nahi badlega); password khaali chhod do to purana password bana rehta hai.
- Ye command `DatabaseSeeder` ka hissa nahi hai — admin account kabhi galti se create na ho.

Har shell (cmd / PowerShell / Git Bash) me safe hai, kyunki `--option=value` ko quoting nahi chahiye.

---

## Step 12 — Storage link *(optional — is project me zaroori nahi)*

Is app me saara permanent media **Cloudinary** pe jata hai; code me `Storage::disk('public')` kahin use nahi hota. To `public/storage` symlink skip kar sakte ho.

Phir bhi chahiye to terminal **Run as Administrator** se:

```bash
php artisan storage:link
```

`symlink(): A required privilege is not held by the client` aaye to ya admin terminal use karo, ya **Settings → Privacy & security → For developers → Developer Mode** ON kar do.

---

## Step 13 — Frontend build

```bash
npm ci
npm run build
```

- `npm ci` use karo — `package-lock.json` committed hai, isse exact same versions milenge.
- Repo ke `.npmrc` me `ignore-scripts=true` hai — **intentional hai, hatao mat.**
- `public/build/` gitignored hai, isliye har fresh clone pe ek baar build zaroori hai.

**Hot reload chahiye:**

```bash
npm run dev
```

Alag terminal me chhod do. Vite `http://localhost:5173` pe chalega; `maverick.test` apne aap usse assets uthayega (laravel-vite-plugin `.test` origins ko CORS allow karta hai). Kaam khatam hone pe `Ctrl+C`, phir `npm run build`.

---

## Step 14 — Chalao 🚀

Laragon me Apache + MySQL green hone chahiye.

- **Website:** http://maverick.test
- **Admin:** http://maverick.test/admin
- **Health:** http://maverick.test/up → `200`

Login: `admin@maverick.test` / `Password@123`

### Apache ke bina (quick alternative)

```bash
php artisan serve
# http://127.0.0.1:8000
```
(Is case me `.env` me `APP_URL=http://127.0.0.1:8000` kar lena.)

---

## Daily workflow

```bash
cd C:\laragon\www\maverick
git pull origin main
composer install
php artisan migrate        # schema + settings dono
npm ci
npm run dev                # ya npm run build
```

Sab ek saath (server + queue + logs + vite):

```bash
composer run dev
```

`.env` / config badla ho to:

```bash
php artisan optimize:clear
```

Tests aur code style:

```bash
php artisan test           # sqlite :memory: use karta hai
./vendor/bin/pint
```

---

## Troubleshooting

### PHP / Composer

| Error | Fix |
|---|---|
| `Class "Pdo\Mysql" not found` (har request/artisan pe) | PHP 8.3 chal raha hai. Laragon → PHP → Version → **8.4**. Verify: `php -r "echo class_exists('Pdo\Mysql')?'OK':'OLD';"` |
| `VCRUNTIME140.dll was not found` / php.exe silently band | VC++ Redistributable missing → https://aka.ms/vs/17/release/vc_redist.x64.exe |
| `Your requirements could not be resolved ... php ^8.3` | Composer purane PHP se bandha hai → Composer-Setup dobara, PHP 8.4 ka `php.exe` select |
| `ext-intl missing` / `Class "NumberFormatter" not found` | `php.ini` me `extension=intl` uncomment + Laragon restart |
| `ext-zip missing` | `extension=zip` uncomment |
| `Unable to load dynamic library ... ext\php_gd.dll` | `extension_dir` galat hai → absolute path do (Step 1d) |
| `Allowed memory size exhausted` (composer) | `php.ini` → `memory_limit = 512M` |
| Composer `curl error 60` / SSL | `php.ini` me `curl.cainfo="C:\laragon\etc\ssl\cacert.pem"` aur `openssl.cafile` wahi |
| `php artisan test` me `could not find driver` | `extension=pdo_sqlite` + `extension=sqlite3` uncomment |

### Database

| Error | Fix |
|---|---|
| `[2002] No connection could be made` | Laragon me MySQL start nahi, ya port 3307 hai → `.env` ka `DB_PORT` match karao |
| `[1049] Unknown database 'maverick_db'` | Step 8 skip ho gaya |
| `[1045] Access denied for user 'root'` | Laragon root ka password khaali hota hai → `DB_PASSWORD=` khaali rakho |
| Dump import ke baad site khaali | `php artisan migrate` chalao (settings migrations pending hongi) + `php artisan optimize:clear` |
| Homepage pe sab placeholder text | `migrate` nahi chala — `database/settings/` ki 93 migrations pending hain. `php artisan migrate:status` check karo |
| PowerShell me `The '<' operator is reserved` | `cmd /c "mysql ... < dump.sql"` use karo |

### Apache / vhost

| Error | Fix |
|---|---|
| `maverick.test` DNS error | Laragon ko **Run as Administrator** se chalao → Reload. Ya `C:\Windows\System32\drivers\etc\hosts` me `127.0.0.1 maverick.test` add karo |
| Laragon ka default page dikhta hai | vhost ka `DocumentRoot` `.../maverick/public` hona chahiye → `sites-enabled` conf check karo → Reload |
| Apache start hi nahi hota | Port 80 busy (IIS / "World Wide Web Publishing Service" band karo), ya VS16/VS17 mismatch (Step 1e ka note) |
| `The stream or file "storage/logs/laravel.log" could not be opened` | `storage/` + `bootstrap/cache/` writable chahiye. Project ko **OneDrive folder me mat rakho**, aur antivirus me `C:\laragon` exclude karo |
| `symlink(): A required privilege is not held` | Admin terminal, ya Developer Mode ON (ya storage:link skip hi kar do — Step 12) |

### Node / Vite

| Error | Fix |
|---|---|
| `node -v` purana dikhta hai despite nvm | Laragon ka bundled Node shadow kar raha hai → `C:\laragon\bin\nodejs` rename karo (Step 4) |
| `crypto.hash is not a function` / Vite engine warning | Node `>=22.12` chahiye → `nvm install 22.20.0 && nvm use 22.20.0` |
| Page bina CSS ke | `npm run build` nahi chala; ya `npm run dev` band hua aur `public/hot` file bachi hai → use delete karo |
| `git clone` pe `Filename too long` | `git config --global core.longpaths true` |

### Admin panel

| Error | Fix |
|---|---|
| Login ke baad **403 Forbidden** | User ka `is_admin` false hai → `php artisan admin:create --email=<wahi email>` |
| `/admin` pe redirect loop ya login page nahi | `php artisan optimize:clear` + `php artisan filament:upgrade` |
| Admin me Filament ka CSS/JS missing | `php artisan filament:assets` |
| Images blank / upload fail | `.env` me Cloudinary keys missing hain |

---

## Quick reference — saare commands

```bash
# clone
cd C:\laragon\www
git clone https://github.com/anoopuri21/maverick.git
cd maverick

# php deps
composer install

# database
mysql -u root -e "CREATE DATABASE maverick_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# env  (DB_CONNECTION=mysql, DB_DATABASE=maverick_db, DB_USERNAME=root, DB_PASSWORD=, APP_URL=http://maverick.test)
copy .env.example .env
php artisan key:generate

# schema + settings + data
php artisan migrate --seed
#   ya dump se:
#   cmd /c "mysql -u root --default-character-set=utf8mb4 maverick_db < C:\path\dump.sql"
#   php artisan migrate

# admin
php artisan admin:create --email=admin@maverick.test --password=Password@123

# frontend
npm ci
npm run build

# verify
php artisan about
start http://maverick.test
start http://maverick.test/admin
```

---

## Automation script

Clone ho jaane ke baad (Step 6 ke baad) baaki sab ek command me:

```powershell
cd C:\laragon\www\maverick
powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1
```

SQL dump se setup:

```powershell
powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1 -SqlDump "C:\Users\You\Downloads\maverick_dump.sql" -FreshDatabase
```

Script pehle environment validate karta hai (PHP 8.4 + `Pdo\Mysql` class, extensions, Composer ka PHP, MySQL server reachable, Node version) aur koi problem ho to **kuch change kiye bina** clear fix message ke saath ruk jata hai. Idempotent hai — dobara chala sakte ho.

Saare options: `Get-Help .\scripts\windows-setup.ps1 -Detailed`
