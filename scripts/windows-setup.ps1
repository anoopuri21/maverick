<#
.SYNOPSIS
    Maverick — Windows + Laragon local setup automation.

.DESCRIPTION
    Repo clone ho jaane ke baad ye script baaki sab kar deta hai:
    environment checks -> composer install -> MySQL database -> .env ->
    app key -> migrations/seed (ya SQL dump import) -> admin user ->
    storage:link -> npm install + build.

    Script idempotent hai: dobara chala sakte ho, kuch toote ga nahi.

.PARAMETER Database
    MySQL database ka naam. Default: maverick_db

.PARAMETER DbUser
    MySQL user. Default: root

.PARAMETER DbPassword
    MySQL password. Laragon me default khaali hota hai.

.PARAMETER DbPort
    MySQL port. Default: 3306

.PARAMETER SqlDump
    Kisi .sql dump ka path. Diya to migrate --seed ki jagah dump import hoga,
    uske baad pending migrations chalengi.

.PARAMETER FreshDatabase
    Database ko DROP karke dobara banata hai. (Data chala jayega!)

.PARAMETER AdminEmail
    Filament admin ka email. Default: admin@maverick.test

.PARAMETER AdminPassword
    Filament admin ka password. Default: Password@123

.PARAMETER SkipNpm
    npm install / npm run build skip karo.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\windows-setup.ps1 -SqlDump "C:\dumps\maverick.sql" -FreshDatabase
#>

[CmdletBinding()]
param(
    [string] $Database      = 'maverick_db',
    [string] $DbUser        = 'root',
    [string] $DbPassword    = '',
    [int]    $DbPort        = 3306,
    [string] $SqlDump       = '',
    [switch] $FreshDatabase,
    [string] $AdminEmail    = 'admin@maverick.test',
    [string] $AdminPassword = 'Password@123',
    [switch] $SkipNpm
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

function Get-MysqlArgs {
    $a = @("-u$DbUser", "-P$DbPort", '-h127.0.0.1')
    if ($DbPassword -ne '') { $a += "-p$DbPassword" }
    return $a
}

# Repo root = is script ka parent folder
$root = Split-Path -Parent $PSScriptRoot
Set-Location $root

Write-Host ""
Write-Host "  Maverick — Windows/Laragon setup" -ForegroundColor Magenta
Write-Host "  $root" -ForegroundColor DarkGray

# ------------------------------------------------------- 1. environment ----
Write-Step '1/9  Environment check'

$fatal = @()

if (-not (Test-Command 'php')) {
    $fatal += 'php PATH me nahi mila. Laragon Terminal use karo (Laragon -> right-click -> Terminal).'
} else {
    $phpVersion = (& php -r 'echo PHP_VERSION;')
    $phpMajorMinor = [version] ((& php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;'))
    if ($phpMajorMinor -lt [version]'8.4') {
        $fatal += "PHP $phpVersion mila, lekin 8.4+ chahiye. config/database.php me Pdo\Mysql class use hoti hai jo sirf PHP 8.4+ me hai. Laragon -> PHP -> Version se 8.4 select karo. (docs/WINDOWS_LARAGON_SETUP.md Step 1)"
    } else {
        Write-Ok "PHP $phpVersion"
    }

    $required = @('bcmath','curl','exif','fileinfo','gd','intl','mbstring','openssl','pdo_mysql','zip','tokenizer','dom')
    $loaded   = (& php -r 'echo implode(",", get_loaded_extensions());') -split ','
    $missing  = $required | Where-Object { $loaded -notcontains $_ }
    if ($missing.Count -gt 0) {
        $fatal += "PHP extensions missing: $($missing -join ', '). php.ini me inhe uncomment karke Laragon restart karo."
    } else {
        Write-Ok "PHP extensions sab present"
    }
}

if (-not (Test-Command 'composer')) {
    $fatal += 'composer nahi mila. https://getcomposer.org/Composer-Setup.exe se install karo (PHP 8.4 ka php.exe select karna).'
} else {
    Write-Ok ((& composer -V --no-ansi) | Select-Object -First 1)
}

if (-not (Test-Command 'mysql')) {
    $fatal += 'mysql client nahi mila. Laragon ka MySQL start karo aur Laragon Terminal use karo.'
} else {
    Write-Ok 'mysql client mil gaya'
}

if (-not $SkipNpm) {
    if (-not (Test-Command 'node')) {
        $fatal += 'node nahi mila. nvm install 22.20.0 && nvm use 22.20.0'
    } else {
        $nodeRaw = (& node -v).TrimStart('v')
        $nodeVer = [version] $nodeRaw
        if ($nodeVer -lt [version]'22.12.0' -and -not ($nodeVer -ge [version]'20.19.0' -and $nodeVer -lt [version]'21.0.0')) {
            $fatal += "Node v$nodeRaw mila. Vite 8 ko ^20.19 ya >=22.12 chahiye. Chalao: nvm install 22.20.0 ; nvm use 22.20.0"
        } else {
            Write-Ok "Node v$nodeRaw"
        }
    }
}

if ($fatal.Count -gt 0) {
    Write-Host ''
    Write-Err 'Setup rok diya — pehle ye theek karo:'
    $fatal | ForEach-Object { Write-Host "      - $_" -ForegroundColor Red }
    Write-Host ''
    Write-Host '  Full guide: docs\WINDOWS_LARAGON_SETUP.md' -ForegroundColor DarkGray
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
    Write-Warn "FreshDatabase on hai — '$Database' DROP ho raha hai"
    Invoke-Checked 'mysql' ($my + @('-e', "DROP DATABASE IF EXISTS ``$Database``;")) 'DROP DATABASE'
}

Invoke-Checked 'mysql' ($my + @('-e', "CREATE DATABASE IF NOT EXISTS ``$Database`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;")) 'CREATE DATABASE'
Write-Ok "Database ready (utf8mb4 / utf8mb4_unicode_ci)"

# ------------------------------------------------------ 4. .env -----------
Write-Step '4/9  .env file'

if (-not (Test-Path '.env')) {
    Copy-Item '.env.example' '.env'
    Write-Ok '.env banaya (.env.example se copy)'
} else {
    Write-Ok '.env already maujood hai — DB values update kar raha hoon'
}

function Set-EnvValue ([string] $key, [string] $value) {
    $lines = Get-Content '.env'
    $hit = $false
    $out = foreach ($line in $lines) {
        if ($line -match "^\s*#?\s*$([regex]::Escape($key))=") {
            $hit = $true
            "$key=$value"
        } else {
            $line
        }
    }
    if (-not $hit) { $out += "$key=$value" }

    # BOM ke bina likhna zaroori hai — warna dotenv pehli line ko parse nahi kar paata.
    $utf8NoBom = New-Object System.Text.UTF8Encoding($false)
    [System.IO.File]::WriteAllText(
        (Join-Path (Get-Location).Path '.env'),
        (($out -join "`n") + "`n"),
        $utf8NoBom
    )
}

Set-EnvValue 'DB_CONNECTION' 'mysql'
Set-EnvValue 'DB_HOST'       '127.0.0.1'
Set-EnvValue 'DB_PORT'       "$DbPort"
Set-EnvValue 'DB_DATABASE'   $Database
Set-EnvValue 'DB_USERNAME'   $DbUser
Set-EnvValue 'DB_PASSWORD'   $DbPassword
Set-EnvValue 'APP_URL'       'http://maverick.test'
Write-Ok 'DB_* aur APP_URL set ho gaye'

# ------------------------------------------------------ 5. app key --------
Write-Step '5/9  APP_KEY'
$envText = Get-Content '.env' -Raw
if ($envText -match '(?m)^APP_KEY=base64:.+$') {
    Write-Ok 'APP_KEY already set hai — chhod raha hoon (regenerate karne se encrypted data toot jata hai)'
} else {
    Invoke-Checked 'php' @('artisan','key:generate','--ansi') 'key:generate'
    Write-Ok 'APP_KEY generate ho gayi'
}

# ------------------------------------------------- 6. schema + data -------
if ($SqlDump -ne '') {
    Write-Step "6/9  SQL dump import — $SqlDump"
    if (-not (Test-Path $SqlDump)) { throw "SQL dump nahi mila: $SqlDump" }

    $dumpFull = (Resolve-Path $SqlDump).Path
    $sizeMb = [math]::Round((Get-Item $dumpFull).Length / 1MB, 1)
    Write-Host "    Importing $sizeMb MB... (bada dump ho to time lagega)" -ForegroundColor DarkGray

    # cmd.exe ke through pipe karte hain taaki `<` redirect kaam kare
    $pwdPart = if ($DbPassword -ne '') { "-p$DbPassword" } else { '' }
    $cmd = "mysql -h127.0.0.1 -P$DbPort -u$DbUser $pwdPart --max_allowed_packet=512M --default-character-set=utf8mb4 `"$Database`" < `"$dumpFull`""
    & cmd.exe /c $cmd
    if ($LASTEXITCODE -ne 0) { throw "SQL dump import fail hua (exit code $LASTEXITCODE)." }
    Write-Ok 'Dump import ho gaya'

    Write-Host '    Pending migrations chala raha hoon...' -ForegroundColor DarkGray
    Invoke-Checked 'php' @('artisan','migrate','--force','--ansi') 'migrate'
    Write-Ok 'Migrations up to date'
} else {
    Write-Step '6/9  Migrations + seeders'
    Invoke-Checked 'php' @('artisan','migrate','--seed','--force','--ansi') 'migrate --seed'
    Write-Ok 'Schema + demo data taiyaar'
}

# ------------------------------------------------------ 7. admin ---------
Write-Step "7/9  Admin user — $AdminEmail"
$escEmail = $AdminEmail -replace "'", "\'"
$escPass  = $AdminPassword -replace "'", "\'"
$snippet  = "\App\Models\User::updateOrCreate(['email' => '$escEmail'], ['name' => 'Admin', 'password' => '$escPass', 'is_admin' => true]);"
Invoke-Checked 'php' @('artisan','tinker','--execute', $snippet) 'admin user banana'
Write-Ok "Admin ready -> $AdminEmail / $AdminPassword"

# ------------------------------------------------- 8. storage:link --------
Write-Step '8/9  storage:link'
try {
    Invoke-Checked 'php' @('artisan','storage:link','--ansi') 'storage:link'
    Write-Ok 'public/storage symlink ban gaya'
} catch {
    Write-Warn 'storage:link fail hua — Windows symlink permission chahiye.'
    Write-Warn 'Fix: terminal "Run as Administrator" se kholo aur chalao: php artisan storage:link'
    Write-Warn '     (ya Settings -> Privacy & security -> For developers -> Developer Mode ON)'
}

# ------------------------------------------------------ 9. frontend -------
if ($SkipNpm) {
    Write-Step '9/9  Frontend build — skip (-SkipNpm)'
} else {
    Write-Step '9/9  npm install + build'
    $npm = if (Test-Command 'npm.cmd') { 'npm.cmd' } else { 'npm' }
    Invoke-Checked $npm @('install','--no-fund','--no-audit') 'npm install'
    Invoke-Checked $npm @('run','build') 'npm run build'
    Write-Ok 'public/build taiyaar'
}

# ----------------------------------------------------------- summary ------
Write-Host ''
Write-Host '  ======================================================' -ForegroundColor Green
Write-Host '   Setup complete!' -ForegroundColor Green
Write-Host '  ======================================================' -ForegroundColor Green
Write-Host ''
Write-Host '   Site       : http://maverick.test'
Write-Host '   Admin      : http://maverick.test/admin'
Write-Host '   Health     : http://maverick.test/up'
Write-Host ''
Write-Host "   Login      : $AdminEmail"
Write-Host "   Password   : $AdminPassword"
Write-Host ''
Write-Host "   Database   : $Database @ 127.0.0.1:$DbPort"
Write-Host ''
Write-Host '   Next:' -ForegroundColor Yellow
Write-Host '     - Laragon -> Reload (taaki maverick.test vhost bane)'
Write-Host '     - .env me CLOUDINARY_* keys daalo (images ke liye zaroori hai)'
Write-Host '     - Hot reload chahiye to: npm run dev'
Write-Host ''
Write-Host '   Guide: docs\WINDOWS_LARAGON_SETUP.md' -ForegroundColor DarkGray
Write-Host ''
