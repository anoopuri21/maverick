<#
.SYNOPSIS
    Maverick - Windows + Laragon local setup automation.

.DESCRIPTION
    Repo clone ho jaane ke baad ye script baaki sab kar deta hai:
    environment validation -> composer install -> MySQL database -> .env ->
    app key -> migrations (schema + spatie settings) + seeders, ya SQL dump
    import -> admin user -> npm ci + build.

    Idempotent hai: dobara chala sakte ho, kuch toote ga nahi.
    Validation fail hone pe kuch bhi change kiye bina exit karta hai.

.PARAMETER Database
    MySQL database ka naam. Default: maverick_db

.PARAMETER DbUser
    MySQL user. Default: root

.PARAMETER DbPassword
    MySQL password. Laragon me default khaali hota hai.

.PARAMETER DbPort
    MySQL port. Default: 3306

.PARAMETER SqlDump
    Kisi .sql dump ka path. Diya to `migrate --seed` ki jagah dump import hoga,
    uske baad sirf pending migrations chalengi.

.PARAMETER FreshDatabase
    Database ko DROP karke dobara banata hai. (Saara data chala jayega!)

.PARAMETER KeepAppKey
    APP_KEY generate mat karo. Use karo jab dump ke saath purana APP_KEY diya ho
    (encrypted settings warna decrypt nahi hongi).

.PARAMETER AdminEmail
    Filament admin ka email. Default: admin@maverick.test

.PARAMETER AdminPassword
    Filament admin ka password. Default: Password@123

.PARAMETER AppUrl
    APP_URL. Default: http://maverick.test

.PARAMETER SkipNpm
    npm ci / npm run build skip karo.

.PARAMETER StorageLink
    `php artisan storage:link` bhi chalao (is project me zaroori nahi - media
    Cloudinary pe hai - aur Windows pe admin terminal maangta hai).

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1 -SqlDump "C:\dumps\maverick.sql" -FreshDatabase -KeepAppKey
#>

[CmdletBinding()]
param(
    [string] $Database      = 'maverick_db',
    [string] $DbUser        = 'root',
    [string] $DbPassword    = '',
    [int]    $DbPort        = 3306,
    [string] $SqlDump       = '',
    [switch] $FreshDatabase,
    [switch] $KeepAppKey,
    [string] $AdminEmail    = 'admin@maverick.test',
    [string] $AdminPassword = 'Password@123',
    [string] $AppUrl        = 'http://maverick.test',
    [switch] $SkipNpm,
    [switch] $StorageLink
)

$ErrorActionPreference = 'Stop'

# ---------------------------------------------------------------- helpers --
function Write-Step ($m) { Write-Host "`n==> $m" -ForegroundColor Cyan }
function Write-Ok   ($m) { Write-Host "    OK  $m" -ForegroundColor Green }
function Write-Warn ($m) { Write-Host "    !!  $m" -ForegroundColor Yellow }
function Write-Err  ($m) { Write-Host "    XX  $m" -ForegroundColor Red }

function Test-Command ($name) {
    return [bool] (Get-Command $name -ErrorAction SilentlyContinue)
}

function Invoke-Checked ($exe, [string[]] $cmdArgs, [string] $what) {
    & $exe @cmdArgs
    if ($LASTEXITCODE -ne 0) { throw "$what fail hua (exit code $LASTEXITCODE)." }
}

# Native output capture jo stderr pe throw na kare (PS 5.1 quirk).
function Get-NativeOutput ($exe, [string[]] $cmdArgs) {
    $old = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try   { return (& $exe @cmdArgs 2>&1 | Out-String) }
    finally { $ErrorActionPreference = $old }
}

function Get-MysqlArgs {
    $a = @('-h127.0.0.1', "-P$DbPort", "-u$DbUser")
    if ($DbPassword -ne '') { $a += "-p$DbPassword" }
    return $a
}

# Repo root = is script ka parent folder
$root = Split-Path -Parent $PSScriptRoot
Set-Location $root

Write-Host ''
Write-Host '  Maverick - Windows/Laragon setup' -ForegroundColor Magenta
Write-Host "  $root" -ForegroundColor DarkGray

# ------------------------------------------------------- 1. environment ----
Write-Step '1/9  Environment validation'

$fatal = @()

# --- PHP ---
if (-not (Test-Command 'php')) {
    $fatal += 'php PATH me nahi mila. Laragon Terminal use karo (Laragon -> right-click -> Terminal).'
} else {
    $phpOk = $false
    try {
        $phpVersion = ((& php -r 'echo PHP_VERSION;') | Out-String).Trim()
        $phpOk = -not [string]::IsNullOrWhiteSpace($phpVersion)
    } catch { $phpOk = $false }

    if (-not $phpOk) {
        $fatal += 'php.exe chal nahi raha. Aksar Visual C++ Redistributable missing hota hai -> https://aka.ms/vs/17/release/vc_redist.x64.exe (install karke reboot).'
    } else {
        $phpMajorMinor = [version] ((& php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;").Trim())

        # Asli test: config/database.php ko Pdo\Mysql class chahiye (PHP 8.4+).
        $pdoClass = ((& php -r "echo class_exists('Pdo\Mysql') ? 'yes' : 'no';") | Out-String).Trim()

        if ($phpMajorMinor -lt [version]'8.4' -or $pdoClass -ne 'yes') {
            $fatal += "PHP $phpVersion mila, lekin 8.4+ chahiye. config/database.php me 'Pdo\Mysql' class use hoti hai (PHP 8.4+ only) aur Laravel har request pe config load karta hai, to 8.3 pe app boot hi nahi hogi. Laragon -> PHP -> Version se 8.4 select karo. (docs/WINDOWS_LARAGON_SETUP.md Step 1)"
        } else {
            Write-Ok "PHP $phpVersion (Pdo\Mysql available)"
        }

        # Windows pe dom/tokenizer/ctype/json built-in hote hain, isliye list me nahi.
        $required = @('bcmath','curl','exif','fileinfo','gd','intl','mbstring','openssl','pdo_mysql','zip','pdo_sqlite')
        $loaded   = (((& php -r "echo implode(',', get_loaded_extensions());") | Out-String) -split ',') | ForEach-Object { $_.Trim().ToLower() }
        $missing  = $required | Where-Object { $loaded -notcontains $_ }
        if ($missing.Count -gt 0) {
            $fatal += "PHP extensions missing: $($missing -join ', '). php.ini me inhe uncomment karo (extension_dir absolute path ho) aur Laragon restart karo."
        } else {
            Write-Ok 'PHP extensions sab present'
        }
    }
}

# --- Composer (aur uska PHP) ---
if (-not (Test-Command 'composer')) {
    $fatal += 'composer nahi mila -> https://getcomposer.org/Composer-Setup.exe (installer me PHP 8.4 ka php.exe select karna).'
} else {
    $composerOut = Get-NativeOutput 'composer' @('-V','--no-ansi')
    $composerLine = ($composerOut -split "`n" | Where-Object { $_ -match 'Composer version' } | Select-Object -First 1)
    if ($composerLine) { Write-Ok $composerLine.Trim() } else { Write-Ok 'Composer mil gaya' }
}

# --- MySQL client + server reachable ---
if (-not (Test-Command 'mysql')) {
    $fatal += 'mysql client nahi mila. Laragon ka MySQL start karo aur Laragon Terminal use karo.'
} else {
    $ping = Get-NativeOutput 'mysql' ((Get-MysqlArgs) + @('-e','SELECT 1;'))
    if ($LASTEXITCODE -ne 0) {
        $fatal += "MySQL se connect nahi ho paya (127.0.0.1:$DbPort, user '$DbUser'). Laragon me MySQL start karo, ya -DbPort / -DbPassword pass karo. Detail: $($ping.Trim())"
    } else {
        Write-Ok "MySQL reachable @ 127.0.0.1:$DbPort"
    }
}

# --- Node ---
$npmExe = $null
if (-not $SkipNpm) {
    if (-not (Test-Command 'node')) {
        $fatal += 'node nahi mila -> nvm install 22.20.0 ; nvm use 22.20.0'
    } else {
        $nodeRaw = ((& node -v).Trim()).TrimStart('v')
        $nodeVer = [version] $nodeRaw
        $nodeGood = ($nodeVer -ge [version]'22.12.0') -or
                    ($nodeVer -ge [version]'20.19.0' -and $nodeVer -lt [version]'21.0.0')
        if (-not $nodeGood) {
            $nodePath = (Get-Command node).Source
            $hint = if ($nodePath -like '*laragon*') {
                " Dhyan do: ye Laragon ka bundled Node hai ($nodePath) - 'C:\laragon\bin\nodejs' folder rename kar do taaki nvm wala Node use ho."
            } else { '' }
            $fatal += "Node v$nodeRaw mila. Vite 8 ko ^20.19 ya >=22.12 chahiye. Chalao: nvm install 22.20.0 ; nvm use 22.20.0.$hint"
        } else {
            Write-Ok "Node v$nodeRaw"
        }
        $npmExe = if (Test-Command 'npm.cmd') { 'npm.cmd' } elseif (Test-Command 'npm') { 'npm' } else { $null }
        if (-not $npmExe) { $fatal += 'npm nahi mila.' }
    }
}

if ($fatal.Count -gt 0) {
    Write-Host ''
    Write-Err 'Setup rok diya (kuch change nahi kiya gaya) - pehle ye theek karo:'
    $fatal | ForEach-Object { Write-Host "      - $_" -ForegroundColor Red }
    Write-Host ''
    Write-Host '  Full guide: docs\WINDOWS_LARAGON_SETUP.md' -ForegroundColor DarkGray
    Write-Host ''
    exit 1
}

# ------------------------------------------------------ 2. composer -------
Write-Step '2/9  composer install'
Invoke-Checked 'composer' @('install','--no-interaction','--prefer-dist') 'composer install'
Write-Ok 'PHP dependencies install ho gayi'

# ------------------------------------------------------ 3. database -------
Write-Step "3/9  MySQL database '$Database'"
$my = Get-MysqlArgs

if ($FreshDatabase) {
    Write-Warn "FreshDatabase on hai - '$Database' DROP ho raha hai"
    Invoke-Checked 'mysql' ($my + @('-e', "DROP DATABASE IF EXISTS ``$Database``;")) 'DROP DATABASE'
}

Invoke-Checked 'mysql' ($my + @('-e', "CREATE DATABASE IF NOT EXISTS ``$Database`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;")) 'CREATE DATABASE'
Write-Ok 'Database ready (utf8mb4 / utf8mb4_unicode_ci)'

# ------------------------------------------------------ 4. .env -----------
Write-Step '4/9  .env file'

$envPath = Join-Path $root '.env'

if (-not (Test-Path $envPath)) {
    Copy-Item (Join-Path $root '.env.example') $envPath
    Write-Ok '.env banaya (.env.example se)'
} else {
    Write-Ok '.env already maujood hai - sirf DB_* / APP_URL update kar raha hoon'
}

function Set-EnvValue ([string] $key, [string] $value) {
    $lines = @(Get-Content $envPath)
    $hit = $false
    $out = foreach ($line in $lines) {
        if ($line -match "^\s*#?\s*$([regex]::Escape($key))\s*=") {
            $hit = $true
            "$key=$value"
        } else {
            $line
        }
    }
    if (-not $hit) { $out = @($out) + "$key=$value" }

    # BOM ke bina likhna zaroori hai - warna dotenv pehli line parse nahi kar paata.
    $utf8NoBom = New-Object System.Text.UTF8Encoding($false)
    [System.IO.File]::WriteAllText($envPath, (($out -join "`n") + "`n"), $utf8NoBom)
}

Set-EnvValue 'DB_CONNECTION' 'mysql'
Set-EnvValue 'DB_HOST'       '127.0.0.1'
Set-EnvValue 'DB_PORT'       "$DbPort"
Set-EnvValue 'DB_DATABASE'   $Database
Set-EnvValue 'DB_USERNAME'   $DbUser
Set-EnvValue 'DB_PASSWORD'   $DbPassword
Set-EnvValue 'APP_URL'       $AppUrl
Write-Ok "DB_* set, APP_URL=$AppUrl"

$cloud = (Get-Content $envPath | Where-Object { $_ -match '^CLOUDINARY_CLOUD_NAME=\S' })
if (-not $cloud) {
    Write-Warn 'CLOUDINARY_* keys khaali hain - admin me image upload aur Cloudinary images kaam nahi karengi. Team se keys lekar .env me daal dena.'
}

# ------------------------------------------------------ 5. app key --------
Write-Step '5/9  APP_KEY'
$envText = Get-Content $envPath -Raw
if ($envText -match '(?m)^APP_KEY=base64:.+$') {
    Write-Ok 'APP_KEY already set - chhod raha hoon (regenerate karne se encrypted data toot jata hai)'
} elseif ($KeepAppKey) {
    Write-Warn 'APP_KEY khaali hai par -KeepAppKey diya gaya - generate nahi kiya. Dump wala purana APP_KEY khud .env me paste karo.'
} else {
    Invoke-Checked 'php' @('artisan','key:generate','--ansi') 'key:generate'
    Write-Ok 'APP_KEY generate ho gayi'
}

# ------------------------------------------------- 6. schema + data -------
if ($SqlDump -ne '') {
    Write-Step "6/9  SQL dump import"
    if (-not (Test-Path $SqlDump)) { throw "SQL dump nahi mila: $SqlDump" }

    $dumpFull = (Resolve-Path $SqlDump).Path
    $sizeMb = [math]::Round((Get-Item $dumpFull).Length / 1MB, 1)
    Write-Host "    $dumpFull ($sizeMb MB) import ho raha hai..." -ForegroundColor DarkGray

    # cmd.exe ke through, kyunki PowerShell me '<' redirect support nahi hai.
    $pwdPart = if ($DbPassword -ne '') { "-p$DbPassword" } else { '' }
    $cmd = "mysql -h127.0.0.1 -P$DbPort -u$DbUser $pwdPart --max_allowed_packet=512M --default-character-set=utf8mb4 `"$Database`" < `"$dumpFull`""
    & cmd.exe /c $cmd
    if ($LASTEXITCODE -ne 0) { throw "SQL dump import fail hua (exit code $LASTEXITCODE)." }
    Write-Ok 'Dump import ho gaya'

    Write-Host '    Pending migrations (schema + spatie settings) chala raha hoon...' -ForegroundColor DarkGray
    Invoke-Checked 'php' @('artisan','migrate','--force','--ansi') 'migrate'
    Write-Ok 'Migrations up to date'
} else {
    Write-Step '6/9  Migrations (schema + settings) + seeders'
    Write-Host '    Note: database/settings/ ki ~93 spatie migrations bhi chalengi - 1-3 min lag sakte hain.' -ForegroundColor DarkGray
    Invoke-Checked 'php' @('artisan','migrate','--seed','--force','--ansi') 'migrate --seed'
    Write-Ok 'Schema + site content + catalog data taiyaar'
}

# ------------------------------------------------------ 7. admin ---------
Write-Step "7/9  Admin user - $AdminEmail"
# --option=value args ko koi shell quoting nahi chahiye -> har shell me safe.
Invoke-Checked 'php' @(
    'artisan','admin:create',
    "--email=$AdminEmail",
    "--password=$AdminPassword",
    '--ansi'
) 'admin:create'
Write-Ok "Admin ready -> $AdminEmail / $AdminPassword"

# ------------------------------------------------- 8. storage:link --------
if ($StorageLink) {
    Write-Step '8/9  storage:link'
    try {
        Invoke-Checked 'php' @('artisan','storage:link','--ansi') 'storage:link'
        Write-Ok 'public/storage symlink ban gaya'
    } catch {
        Write-Warn 'storage:link fail hua - Windows symlink permission chahiye.'
        Write-Warn 'Fix: terminal "Run as Administrator" se kholo, ya Developer Mode ON karo.'
    }
} else {
    Write-Step '8/9  storage:link - skip'
    Write-Host '    Is project me zaroori nahi (media Cloudinary pe hai). Chahiye to -StorageLink pass karo.' -ForegroundColor DarkGray
}

# ------------------------------------------------------ 9. frontend -------
if ($SkipNpm) {
    Write-Step '9/9  Frontend build - skip (-SkipNpm)'
} else {
    Write-Step '9/9  npm ci + build'
    # package-lock.json committed hai -> ci se exact versions milte hain.
    if (Test-Path (Join-Path $root 'package-lock.json')) {
        Invoke-Checked $npmExe @('ci','--no-fund','--no-audit') 'npm ci'
    } else {
        Invoke-Checked $npmExe @('install','--no-fund','--no-audit') 'npm install'
    }
    Invoke-Checked $npmExe @('run','build') 'npm run build'
    Write-Ok 'public/build taiyaar'
}

# ----------------------------------------------------------- summary ------
Write-Host ''
Write-Host '  ======================================================' -ForegroundColor Green
Write-Host '   Setup complete!' -ForegroundColor Green
Write-Host '  ======================================================' -ForegroundColor Green
Write-Host ''
Write-Host "   Site       : $AppUrl"
Write-Host "   Admin      : $AppUrl/admin"
Write-Host "   Health     : $AppUrl/up   (200 aana chahiye)"
Write-Host ''
Write-Host "   Login      : $AdminEmail"
Write-Host "   Password   : $AdminPassword"
Write-Host ''
Write-Host "   Database   : $Database @ 127.0.0.1:$DbPort"
Write-Host ''
Write-Host '   Next:' -ForegroundColor Yellow
Write-Host '     - Laragon -> Reload (taaki maverick.test vhost bane)'
Write-Host '     - .env me CLOUDINARY_* keys daalo'
Write-Host '     - Hot reload: npm run dev'
Write-Host ''
Write-Host '   Guide: docs\WINDOWS_LARAGON_SETUP.md' -ForegroundColor DarkGray
Write-Host ''
