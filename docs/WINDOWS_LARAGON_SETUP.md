# Maverick — Naye Windows Laptop pe Setup (Laragon)

Ye guide bilkul zero se hai. Assume kiya gaya hai ki laptop pe abhi sirf ye installed hai:

- VS Code
- Laragon
- Node 22
- nvm (nvm-windows)

Project stack: **Laravel 13.20 + Filament 3.3 + Livewire 3 + Tailwind 4 + Vite 8**, DB = **MySQL** (Laragon wala), media = **Cloudinary**.

---

## ⚠️ Sabse pehle: 3 cheezein jo log yahan fasate hain

1. **PHP 8.4 chahiye, 8.3 se kaam NAHI chalega.**
   `config/database.php` me `Pdo\Mysql::ATTR_SSL_CA` constant use hua hai. Ye class PHP **8.4** me aayi hai. PHP 8.3 pe MySQL connection karte hi `Error: Class "Pdo\Mysql" not found` milega. Laragon ka bundled PHP aksar purana hota hai — neeche Step 1 me PHP 8.4 add karna hai.
2. **`php artisan storage:link` ko Administrator terminal chahiye** (Windows symlink permission). Warna "Developer Mode" on karo.
3. **Vite 8 ko Node `^20.19` ya `>=22.12` chahiye.** Node 22 hai to bhi `node -v` check karo — agar 22.0–22.11 hai to upgrade karo (Step 4).

---

## Step 0 — Kya already hai, verify karo

Laragon kholo → right-click menu → **Terminal** (ye Laragon ka apna terminal hai, PATH already set hota hai).

```bash
php -v
composer -V
git --version
node -v
npm -v
nvm version
```

Jo bhi "not recognized" bole, uska section neeche hai.

> **Note:** Laragon **Full** edition me Git, Composer, Node, HeidiSQL pehle se aate hain. Laragon **Lite** me sirf Apache + PHP + MySQL hota hai. Check karne ke liye `C:\laragon\bin\` folder kholo — usme `git`, `composer`, `nodejs` folders dikh rahe hain ya nahi.

---

## Step 1 — PHP 8.4 install karo (Laragon ke andar) 🔴 Mandatory

### 1a. Download

👉 **Link:** https://windows.php.net/download#php-8.4

Us page pe **PHP 8.4 (x.y.z)** section dhoondo aur ye wala download karo:

> **VS17 x64 Thread Safe** → `php-8.4.x-Win32-vs17-x64.zip`

❗ **Thread Safe** hi lena hai (Non-Thread-Safe Apache ke saath kaam nahi karega). 32-bit laptop ho to x86 lena.

### 1b. Laragon me daalo

1. ZIP extract karo.
2. Folder ko yahan rakho aur naam exactly aise rakho:
   ```
   C:\laragon\bin\php\php-8.4.x-Win32-vs17-x64\
   ```
   (Laragon isi naming pattern se versions detect karta hai)
3. Us folder ke andar `php.ini-development` ko copy karke **`php.ini`** naam do.

### 1c. php.ini me extensions on karo

`C:\laragon\bin\php\php-8.4.x-Win32-vs17-x64\php.ini` ko VS Code me kholo.

Pehle extension directory uncomment karo:

```ini
extension_dir = "ext"
```

Phir in lines ke aage ka `;` hata do (Ctrl+F se dhoondo `;extension=`):

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

Aur ye values set/badlo:

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

**Kyun ye extensions:** `intl` + `mbstring` + `dom` → Filament/Carbon/Spatie, `gd` + `exif` → image handling, `curl` + `openssl` → Cloudinary API, `zip` → Composer, `pdo_mysql` → DB, `sqlite3` → tests (phpunit.xml sqlite use karta hai).

### 1d. Laragon me PHP 8.4 select karo

Laragon → right-click → **PHP → Version → php-8.4.x-Win32-vs17-x64** → phir **Stop All** → **Start All**.

Verify (Laragon Terminal me):

```bash
php -v          # PHP 8.4.x hona chahiye
php -m          # list me intl, gd, curl, zip, pdo_mysql, mbstring dikhne chahiye
```

---

## Step 2 — Composer install karo

Agar `composer -V` kaam nahi kar raha:

👉 **Link:** https://getcomposer.org/Composer-Setup.exe

Installer chalao:
- Jab PHP path pooche → `C:\laragon\bin\php\php-8.4.x-Win32-vs17-x64\php.exe` select karo (bahut important — PHP 8.4 wala hi).
- "Add to PATH" tick rehne do.
- Proxy wala page skip.

Install ke baad **naya terminal** kholo:

```bash
composer -V        # Composer version 2.x
composer config -g -l | findstr php    # ya: php -v check karke confirm
```

Thoda fast banane ke liye (optional):

```bash
composer config -g process-timeout 2000
```

---

## Step 3 — Git install karo

Agar `git --version` kaam nahi kar raha:

👉 **Link:** https://git-scm.com/download/win → "64-bit Git for Windows Setup"

Installer me ye choices lo:
- Editor: **Use Visual Studio Code**
- Default branch name: `main`
- PATH: **Git from the command line and also from 3rd-party software**
- Line endings: **Checkout as-is, commit Unix-style line endings**
  (repo ka `.gitattributes` `eol=lf` enforce karta hai, isliye ye safe option hai)
- Credential helper: **Git Credential Manager**

Install ke baad:

```bash
git --version
git config --global user.name "Tumhara Naam"
git config --global user.email "tum@example.com"
git config --global core.autocrlf false
```

---

## Step 4 — Node version pakka karo (nvm se)

Vite 8 ko Node `^20.19.0 || >=22.12.0` chahiye.

```bash
node -v
```

Agar `v22.12.0` se chhota hai:

```bash
nvm install 22.20.0
nvm use 22.20.0
node -v
npm -v
```

> nvm-windows me `nvm use` ke liye terminal **Administrator** me chalana padta hai.

---

## Step 5 — Optional but recommended tools

| Tool | Kyun | Link |
|---|---|---|
| **HeidiSQL** | DB GUI, SQL dump import/export. Laragon Full me already hota hai (`C:\laragon\bin\heidisql`) | https://www.heidisql.com/download.php |
| **DBeaver CE** | HeidiSQL ka better alternative, cross-DB | https://dbeaver.io/download/ |
| **Windows Terminal** | Laragon/cmd se behtar terminal | Microsoft Store |

### VS Code extensions (terminal me paste kar do)

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

## Step 6 — Project clone karo

Laragon ka web root `C:\laragon\www` hai. Project yahin clone karna hai taaki Laragon auto virtual host bana de.

Laragon → right-click → **Terminal**:

```bash
cd C:\laragon\www
git clone https://github.com/anoopuri21/maverick.git
cd maverick
```

Private repo hai to Git Credential Manager browser kholega — GitHub se login kar lena.

Specific branch chahiye to:

```bash
git clone -b main https://github.com/anoopuri21/maverick.git
# ya existing clone me:
git fetch origin
git checkout main
```

### Virtual host

Laragon → right-click → **Reload** (ya **Stop All** → **Start All**).

Laragon khud `http://maverick.test` bana dega aur Laravel detect karke document root ko `public/` pe point kar dega.

Agar `maverick.test` na khule:
- Laragon → **Menu → Apache → sites-enabled** → `auto.maverick.test.conf` check karo, `DocumentRoot` `C:/laragon/www/maverick/public` hona chahiye.
- Laragon → **Preferences → General → "Auto virtual hosts"** tick hona chahiye.
- Laragon ko **Run as Administrator** se chalao (hosts file edit karne ke liye admin chahiye).

---

## Step 7 — PHP dependencies install

```bash
cd C:\laragon\www\maverick
composer install
```

Pehli baar 2–5 min lagenge. End me `php artisan package:discover` aur `php artisan filament:upgrade` apne aap chalenge — agar yahan DB error aaye to ignore karo, `.env` abhi set nahi hua.

---

## Step 8 — Laragon me Database banao

### Option A — Terminal se (fastest)

Laragon ka MySQL root user **bina password** ka hota hai.

```bash
mysql -u root -e "CREATE DATABASE maverick_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Verify:

```bash
mysql -u root -e "SHOW DATABASES;"
```

### Option B — HeidiSQL se (GUI)

1. Laragon → **Database** button (ya `Menu → MySQL → HeidiSQL`).
2. Session: Host `127.0.0.1`, User `root`, Password **khaali**, Port `3306` → **Open**.
3. Left panel me right-click → **Create new → Database**
   - Name: `maverick_db`
   - Collation: `utf8mb4_unicode_ci`
   - OK.

> Laragon me MySQL start nahi ho raha? Port 3306 kisi aur service ne le rakha hoga (purana MySQL/XAMPP). Laragon → **Preferences → Services & Ports** me MySQL port `3307` kar do aur `.env` me `DB_PORT=3307` likh dena.

---

## Step 9 — `.env` banao aur configure karo

```bash
copy .env.example .env
```

Ab `.env` VS Code me kholo (`code .env`) aur ye values set karo:

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

# Cloudinary — team se real keys lo (images in ke bina load nahi hongi)
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_UPLOAD_FOLDER=maverick-academy
```

❗ `.env.example` me default `DB_CONNECTION=sqlite` hai — use **`mysql`** me badalna zaroori hai.

📌 **Cloudinary keys:** ye repo me nahi hain (gitignored). Team lead / existing dev se `CLOUDINARY_*` values maang lo. In ke bina app chalega par admin panel me image upload aur frontend images fail karengi.

App key generate karo:

```bash
php artisan key:generate
```

---

## Step 10 — Database schema + data

Yahan **do raste** hain. Jo situation match kare wahi karo.

### Raasta A — Fresh DB (migrations + seeders se)

Naye developer ke liye normally yahi.

```bash
php artisan migrate --seed
```

Ye 70+ migrations chalayega aur phir `DatabaseSeeder` se Programs, Awards, Faculty Insights, Testimonials wagairah bhar dega.

Status check:

```bash
php artisan migrate:status
mysql -u root -e "USE maverick_db; SHOW TABLES;"
```

### Raasta B — Production/staging ka SQL dump import karna

Agar tumhe koi `.sql` file di gayi hai (asli content ke saath):

**B1. Pehle khaali DB banao** (Step 8) — agar pehle se kuch hai to reset:

```bash
mysql -u root -e "DROP DATABASE IF EXISTS maverick_db; CREATE DATABASE maverick_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**B2. Dump import karo**

Laragon Terminal / cmd me (PowerShell me `<` kaam nahi karta — cmd use karo):

```bash
mysql -u root maverick_db < C:\Users\YourName\Downloads\maverick_dump.sql
```

PowerShell hi use karna ho to:

```powershell
Get-Content C:\Users\YourName\Downloads\maverick_dump.sql | mysql -u root maverick_db
```

Bada dump (>50MB) hai to:

```bash
mysql -u root --max_allowed_packet=512M maverick_db < dump.sql
```

**HeidiSQL se:** DB select karo → **File → Run SQL file...** → `.sql` choose karo → encoding `UTF-8` → Run.

**B3. Import ke baad pending migrations chalao**

```bash
php artisan migrate
php artisan migrate:status
```

**B4. Dump purane host ka hai to URLs/keys theek karo**

`.env` me `APP_URL=http://maverick.test` already set hai. Agar DB me `settings` table ke andar absolute URLs hain to admin panel se update kar lena.

> ⚠️ **Kabhi bhi `php artisan key:generate` mat chalana agar dump ke saath purana `APP_KEY` diya gaya ho** — encrypted settings values decrypt nahi ho paayengi. Aisi situation me `.env` me wahi purana `APP_KEY` paste karo.

---

## Step 11 — Admin user banao

Admin panel `/admin` pe hai aur sirf `is_admin = true` wale users ko access deta hai (`User::canAccessPanel`).

```bash
php artisan tinker --execute="\App\Models\User::updateOrCreate(['email' => 'admin@maverick.test'], ['name' => 'Admin', 'password' => 'Password@123', 'is_admin' => true]);"
```

Ya interactive:

```bash
php artisan tinker
```
```php
\App\Models\User::updateOrCreate(
    ['email' => 'admin@maverick.test'],
    ['name' => 'Admin', 'password' => 'Password@123', 'is_admin' => true]
);
```

(Password model me `hashed` cast hai, isliye plain text dena sahi hai.)

Agar dump import kiya hai aur existing user ko admin banana hai:

```bash
php artisan tinker --execute="\App\Models\User::where('email','tum@example.com')->update(['is_admin' => true]);"
```

---

## Step 12 — Storage link

👉 Terminal ko **Run as Administrator** se kholo (Windows symlink ke liye), phir:

```bash
cd C:\laragon\www\maverick
php artisan storage:link
```

Error `symlink(): A required privilege is not held by the client` aaye to:
- Ya to admin terminal use karo,
- Ya **Settings → Privacy & security → For developers → Developer Mode** ON kar do.

---

## Step 13 — Frontend build (Node)

```bash
npm install
npm run build
```

> Repo ke `.npmrc` me `ignore-scripts=true` hai — ye intentional hai, aise hi rehne do.

Build `public/build/` banata hai (gitignored hai, isliye har fresh clone pe ek baar build zaroori hai).

**Development mode** (hot reload chahiye to):

```bash
npm run dev
```

Isko alag terminal me chhod do — Vite `http://localhost:5173` pe chalega aur `maverick.test` apne aap usse assets uthayega. Kaam khatam hone pe `Ctrl+C`, aur phir production assets ke liye ek baar `npm run build` kar lena.

---

## Step 14 — Chalao 🚀

Laragon me Apache + MySQL green hone chahiye.

- **Website:** http://maverick.test
- **Admin panel:** http://maverick.test/admin
- **Health check:** http://maverick.test/up (200 aana chahiye)

Login: `admin@maverick.test` / `Password@123`

### Laragon vhost ki jagah artisan serve (quick alternative)

```bash
php artisan serve
# http://127.0.0.1:8000
```

---

## Daily workflow (roz ka)

```bash
cd C:\laragon\www\maverick
git pull origin main
composer install
php artisan migrate
npm install
npm run dev          # ya npm run build
```

Kuch weird behave kare to caches saaf karo:

```bash
php artisan optimize:clear
```

Sab kuch ek saath (server + queue + logs + vite) chalane ke liye:

```bash
composer run dev
```

Tests:

```bash
php artisan test
```

Code style:

```bash
./vendor/bin/pint
```

---

## Troubleshooting

| Error | Fix |
|---|---|
| `Class "Pdo\Mysql" not found` | PHP 8.3 chal raha hai. Laragon → PHP → Version → **8.4** select karo (Step 1). |
| `Composer detected issues: Your requirements could not be resolved... php ^8.3` | Composer purane PHP se bandha hai. Composer-Setup dobara chalao aur PHP 8.4 ka `php.exe` choose karo. |
| `ext-intl * -> it is missing` / `Class "NumberFormatter" not found` | `php.ini` me `extension=intl` uncomment karo + Laragon restart. |
| `ext-zip missing` composer install ke time | `extension=zip` uncomment karo. |
| `SQLSTATE[HY000] [2002] No connection could be made` | Laragon me MySQL start nahi hai, ya port 3307 hai. `.env` ka `DB_PORT` match karao. |
| `SQLSTATE[HY000] [1049] Unknown database 'maverick_db'` | Step 8 skip ho gaya — DB banao. |
| `SQLSTATE[42000]: Specified key was too long` | Bahut purana MySQL 5.6 chal raha hai. Laragon → MySQL → Version → **MySQL 8.x** select karo. |
| `maverick.test` nahi khulta / DNS error | Laragon ko **Run as Administrator** se chalao → Reload. Ya `C:\Windows\System32\drivers\etc\hosts` me `127.0.0.1 maverick.test` manually add karo. |
| `maverick.test` pe Laragon ka default page dikhta hai | vhost ka DocumentRoot `.../maverick/public` hona chahiye. `sites-enabled` conf check karo, phir Reload. |
| Port 80 busy / Apache start nahi hota | IIS ya "World Wide Web Publishing Service" band karo, ya Laragon → Preferences → Ports → Apache `8080`. |
| `The stream or file "storage/logs/laravel.log" could not be opened` | `storage/` aur `bootstrap/cache/` folders writable hone chahiye — antivirus/OneDrive sync ko exclude karo. Project OneDrive folder me mat rakho. |
| `symlink(): A required privilege is not held` | Admin terminal se `php artisan storage:link` (Step 12). |
| Vite `crypto.hash is not a function` / engine warning | Node `>=22.12` chahiye → `nvm install 22.20.0 && nvm use 22.20.0`. |
| Page bina CSS ke load hota hai | `npm run build` chalaya nahi. Ya `npm run dev` band ho gaya aur `public/hot` file bachi hai — usse delete kar do. |
| Admin login ke baad **403 Forbidden** | User ka `is_admin` false hai → Step 11 ka update command chalao. |
| Admin me images blank/broken | `.env` me Cloudinary keys missing hain. |
| `composer install` me `Allowed memory size exhausted` | `php.ini` me `memory_limit = 512M` (ya `-1`). |
| Composer SSL error `curl error 60` | `php.ini` me `curl.cainfo` aur `openssl.cafile` ko Laragon ke `cacert.pem` pe point karo (`C:\laragon\etc\ssl\cacert.pem`). |
| `git clone` pe line-ending warnings | `git config --global core.autocrlf false` (repo `.gitattributes` LF enforce karta hai). |

---

## Quick reference — ek nazar me saare commands

```bash
# 1. clone
cd C:\laragon\www
git clone https://github.com/anoopuri21/maverick.git
cd maverick

# 2. php deps
composer install

# 3. db
mysql -u root -e "CREATE DATABASE maverick_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. env
copy .env.example .env
#    .env me: DB_CONNECTION=mysql, DB_DATABASE=maverick_db, DB_USERNAME=root, DB_PASSWORD=, APP_URL=http://maverick.test
php artisan key:generate

# 5. schema + data   (A: fresh)
php artisan migrate --seed
#                    (B: dump se)
# mysql -u root maverick_db < C:\path\to\dump.sql
# php artisan migrate

# 6. admin user
php artisan tinker --execute="\App\Models\User::updateOrCreate(['email'=>'admin@maverick.test'],['name'=>'Admin','password'=>'Password@123','is_admin'=>true]);"

# 7. storage (admin terminal)
php artisan storage:link

# 8. frontend
npm install
npm run build

# 9. open
start http://maverick.test
start http://maverick.test/admin
```

---

## Automation script

Step 6 ke baad (clone ho jaane ke baad) sab kuch ek command me karne ke liye:

```powershell
cd C:\laragon\www\maverick
powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1
```

Dump import karna ho to:

```powershell
powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1 -SqlDump "C:\Users\You\Downloads\maverick_dump.sql"
```

Saare options ke liye script ke top ka comment block padh lo.
