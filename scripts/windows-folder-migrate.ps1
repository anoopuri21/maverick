<#
.SYNOPSIS
    Maverick - purane Windows PC se copy kiye gaye project folder ko naye
    laptop (Laragon) pe chalane layak banata hai.

.DESCRIPTION
    Ye script tab use karo jab tumne GitHub se clone NAHI kiya, balki purane PC
    ka poora folder (vendor/, .env, public/ sab) copy karke
    C:\laragon\www\<project> me rakha hai.

    Ye karta hai (docs/WINDOWS_FOLDER_MIGRATION.md ke Phase 3-6):
      - PHP / Pdo\Mysql / MySQL environment validate
      - purane PC ke stale caches clear (bootstrap/cache, public/hot,
        compiled views, file cache, sessions)
      - database create + optional SQL dump import
      - composer dump-autoload + optimize:clear
      - migrate:status + health report

    Ye KABHI nahi karta:
      - .env ko modify (sirf padhta hai)
      - php artisan key:generate
      - composer update / migrate:fresh / db:seed
      - vendor/ ya public/assets ko touch

.PARAMETER SqlDump
    Purane PC se liye gaye .sql dump ka path. Diya to DB me import hoga.
    Pehle hi import kar chuke ho to ye skip kar do.

.PARAMETER FreshDatabase
    Import se pehle database DROP karke dobara banata hai. (Data chala jayega!)

.PARAMETER Migrate
    Import ke baad `php artisan migrate` bhi chalao (pending migrations ke liye).
    Default: sirf migrate:status dikhata hai, kuch apply nahi karta.

.PARAMETER WhatIfOnly
    Dry run - sirf batata hai kya karega, kuch change nahi karta.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\windows-folder-migrate.ps1 -SqlDump "C:\transfer\maverick_db.sql"

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\windows-folder-migrate.ps1 -WhatIfOnly
#>

[CmdletBinding()]
param(
    [string] $SqlDump = '',
    [switch] $FreshDatabase,
    [switch] $Migrate,
    [switch] $WhatIfOnly
)

$ErrorActionPreference = 'Stop'

function Write-Step ($m) { Write-Host "`n==> $m" -ForegroundColor Cyan }
function Write-Ok   ($m) { Write-Host "    OK  $m" -ForegroundColor Green }
function Write-Warn ($m) { Write-Host "    !!  $m" -ForegroundColor Yellow }
function Write-Err  ($m) { Write-Host "    XX  $m" -ForegroundColor Red }
function Write-Dry  ($m) { Write-Host "    ..  [dry run] $m" -ForegroundColor DarkGray }

function Test-Command ($name) { return [bool] (Get-Command $name -ErrorAction SilentlyContinue) }

function Invoke-Checked ($exe, [string[]] $cmdArgs, [string] $what) {
    if ($WhatIfOnly) { Write-Dry "$exe $($cmdArgs -join ' ')"; return }
    & $exe @cmdArgs
    if ($LASTEXITCODE -ne 0) { throw "$what fail hua (exit code $LASTEXITCODE)." }
}

function Get-NativeOutput ($exe, [string[]] $cmdArgs) {
    $old = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try   { return (& $exe @cmdArgs 2>&1 | Out-String) }
    finally { $ErrorActionPreference = $old }
}

$root = Split-Path -Parent $PSScriptRoot
Set-Location $root

Write-Host ''
Write-Host '  Maverick - folder migration fixer' -ForegroundColor Magenta
Write-Host "  $root" -ForegroundColor DarkGray
if ($WhatIfOnly) { Write-Host '  DRY RUN - kuch change nahi hoga' -ForegroundColor Yellow }

# -------------------------------------------------- 1. sanity: sahi folder?
Write-Step '1/7  Folder sanity check'

$fatal = @()

foreach ($must in @('artisan','composer.json','public\index.php')) {
    if (-not (Test-Path (Join-Path $root $must))) {
        $fatal += "'$must' nahi mila - lagta hai script project root me nahi hai, ya folder copy adhoora hua."
    }
}
if ($fatal.Count -eq 0) { Write-Ok 'Project root sahi lag raha hai' }

if (-not (Test-Path (Join-Path $root 'vendor\autoload.php'))) {
    $fatal += "vendor\autoload.php nahi mila. Is raaste ka matlab hi vendor/ copy karna tha. Ya to vendor/ dobara copy karo, ya 'composer install' chalao."
} else {
    Write-Ok 'vendor/ maujood hai'
}

if (-not (Test-Path (Join-Path $root '.env'))) {
    $fatal += ".env nahi mila. Purane PC se .env copy karo (ZIP ne hidden file chhod di hogi). Iske bina APP_KEY chali jayegi."
} else {
    Write-Ok '.env maujood hai'
}

if (-not (Test-Path (Join-Path $root 'public\assets'))) {
    Write-Warn 'public\assets nahi mila - frontend CSS/JS wahin rehta hai, copy adhoora ho sakta hai.'
}

# ------------------------------------------------------ 2. environment ----
Write-Step '2/7  Environment check'

if (-not (Test-Command 'php')) {
    $fatal += 'php PATH me nahi mila. Laragon Terminal use karo (Laragon -> right-click -> Terminal).'
} else {
    $phpVersion = $null
    try { $phpVersion = ((& php -r 'echo PHP_VERSION;') | Out-String).Trim() } catch { }

    if ([string]::IsNullOrWhiteSpace($phpVersion)) {
        $fatal += 'php.exe chal nahi raha. Aksar VC++ Redistributable missing hota hai -> https://aka.ms/vs/17/release/vc_redist.x64.exe (install + reboot).'
    } else {
        Write-Ok "PHP $phpVersion"

        $pdoMysqlLoaded = ((& php -r "echo extension_loaded('pdo_mysql') ? 'yes' : 'no';") | Out-String).Trim()
        $pdoClass       = ((& php -r "echo class_exists('Pdo\Mysql') ? 'yes' : 'no';") | Out-String).Trim()

        if ($pdoMysqlLoaded -eq 'yes' -and $pdoClass -ne 'yes') {
            $fatal += @"
PHP $phpVersion pe 'Pdo\Mysql' class nahi hai (wo PHP 8.4+ me aayi).
config/database.php usi class ko use karta hai, aur ye script config cache
delete karne wala hai - uske baad app boot hi nahi hogi.

Purane PC pe ye isliye chal raha tha kyunki wahan bootstrap/cache/config.php
maujood tha (tab Laravel config/*.php padhta hi nahi).

Fix: Laragon -> PHP -> Version se PHP 8.4+ select karo, phir script dobara chalao.
Detail: docs/WINDOWS_FOLDER_MIGRATION.md (Phase 2)
"@
        } elseif ($pdoClass -eq 'yes') {
            Write-Ok 'Pdo\Mysql available (PHP 8.4+)'
        } else {
            Write-Warn 'pdo_mysql load nahi hai - MySQL use karna ho to php.ini me extension=pdo_mysql uncomment karo.'
        }
    }
}

if (-not (Test-Command 'composer')) {
    $fatal += 'composer nahi mila -> https://getcomposer.org/Composer-Setup.exe (installer me wahi php.exe select karna jo Laragon me active hai).'
} else {
    Write-Ok 'Composer mil gaya'
}

if (-not (Test-Command 'mysql')) {
    $fatal += 'mysql client nahi mila. Laragon ka MySQL start karo aur Laragon Terminal use karo.'
}

# ------------------------------------------- .env padho (modify nahi karo) --
$dbName = ''; $dbUser = 'root'; $dbPass = ''; $dbPort = '3306'; $appUrl = ''
$hasAppKey = $false

if (Test-Path (Join-Path $root '.env')) {
    foreach ($line in (Get-Content (Join-Path $root '.env'))) {
        if ($line -match '^\s*APP_KEY\s*=\s*base64:\S+') { $hasAppKey = $true }
        elseif ($line -match '^\s*DB_DATABASE\s*=\s*(.*)$') { $dbName = $Matches[1].Trim().Trim('"') }
        elseif ($line -match '^\s*DB_USERNAME\s*=\s*(.*)$') { $dbUser = $Matches[1].Trim().Trim('"') }
        elseif ($line -match '^\s*DB_PASSWORD\s*=\s*(.*)$') { $dbPass = $Matches[1].Trim().Trim('"') }
        elseif ($line -match '^\s*DB_PORT\s*=\s*(.*)$')     { $dbPort = $Matches[1].Trim().Trim('"') }
        elseif ($line -match '^\s*APP_URL\s*=\s*(.*)$')     { $appUrl = $Matches[1].Trim().Trim('"') }
    }

    if ($hasAppKey) {
        Write-Ok 'APP_KEY .env me set hai (script isse haath nahi lagayega)'
    } else {
        $fatal += "APP_KEY .env me nahi mili. Purane PC ki .env se asli APP_KEY copy karo. key:generate MAT chalana - encrypted settings permanently kharab ho jayengi."
    }

    if ($dbName -eq '') {
        $fatal += 'DB_DATABASE .env me nahi mila.'
    } else {
        Write-Ok "DB target: $dbName @ 127.0.0.1:$dbPort (user: $dbUser)"
    }
}

if ($fatal.Count -gt 0) {
    Write-Host ''
    Write-Err 'Ruk gaya (kuch change nahi kiya) - pehle ye theek karo:'
    $fatal | ForEach-Object { Write-Host "      - $_" -ForegroundColor Red; Write-Host '' }
    Write-Host '  Guide: docs\WINDOWS_FOLDER_MIGRATION.md' -ForegroundColor DarkGray
    Write-Host ''
    exit 1
}

function Get-MysqlArgs {
    $a = @('-h127.0.0.1', "-P$dbPort", "-u$dbUser")
    if ($dbPass -ne '') { $a += "-p$dbPass" }
    return $a
}

$ping = Get-NativeOutput 'mysql' ((Get-MysqlArgs) + @('-e','SELECT 1;'))
if ($LASTEXITCODE -ne 0) {
    Write-Host ''
    Write-Err "MySQL se connect nahi hua (127.0.0.1:$dbPort, user '$dbUser')."
    Write-Host "      Laragon me MySQL start karo, ya .env ke DB_PORT / DB_USERNAME / DB_PASSWORD theek karo." -ForegroundColor Red
    Write-Host "      Detail: $($ping.Trim())" -ForegroundColor DarkGray
    exit 1
}
Write-Ok "MySQL reachable @ 127.0.0.1:$dbPort"

# -------------------------------------------------- 3. stale caches clear --
Write-Step '3/7  Purane PC ke stale caches clear'

$targets = @(
    @{ Path = 'bootstrap\cache\*.php';          Why = 'cached config/packages/routes (purane PC ke absolute paths + DB creds)' },
    @{ Path = 'public\hot';                     Why = 'Vite dev marker (warna site bina CSS ke dikhegi)' },
    @{ Path = 'storage\framework\views\*.php';  Why = 'compiled Blade views' },
    @{ Path = 'storage\framework\cache\data\*'; Why = 'file cache (settings cache bhi)' },
    @{ Path = 'storage\framework\sessions\*';   Why = 'purane PC ke login sessions' }
)

foreach ($t in $targets) {
    $full = Join-Path $root $t.Path
    $items = @(Get-ChildItem -Path $full -Force -Recurse -ErrorAction SilentlyContinue |
               Where-Object { $_.Name -ne '.gitignore' })
    if ($items.Count -eq 0) {
        Write-Host "    --  $($t.Path) - already clean" -ForegroundColor DarkGray
        continue
    }
    if ($WhatIfOnly) {
        Write-Dry "delete $($items.Count) item(s) from $($t.Path)  [$($t.Why)]"
    } else {
        $items | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
        Write-Ok "$($t.Path) - $($items.Count) item(s) hataye  [$($t.Why)]"
    }
}

# ------------------------------------------------------ 4. database -------
Write-Step "4/7  Database '$dbName'"
$my = Get-MysqlArgs

if ($FreshDatabase) {
    Write-Warn "FreshDatabase on - '$dbName' DROP ho raha hai"
    Invoke-Checked 'mysql' ($my + @('-e', "DROP DATABASE IF EXISTS ``$dbName``;")) 'DROP DATABASE'
}

Invoke-Checked 'mysql' ($my + @('-e', "CREATE DATABASE IF NOT EXISTS ``$dbName`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;")) 'CREATE DATABASE'
Write-Ok 'Database maujood hai'

if ($SqlDump -ne '') {
    if (-not (Test-Path $SqlDump)) { throw "SQL dump nahi mila: $SqlDump" }
    $dumpFull = (Resolve-Path $SqlDump).Path
    $sizeMb = [math]::Round((Get-Item $dumpFull).Length / 1MB, 1)

    if ($WhatIfOnly) {
        Write-Dry "import $dumpFull ($sizeMb MB) -> $dbName"
    } else {
        Write-Host "    $dumpFull ($sizeMb MB) import ho raha hai..." -ForegroundColor DarkGray
        $pwdPart = if ($dbPass -ne '') { "-p$dbPass" } else { '' }
        $cmd = "mysql -h127.0.0.1 -P$dbPort -u$dbUser $pwdPart --max_allowed_packet=512M --default-character-set=utf8mb4 `"$dbName`" < `"$dumpFull`""
        & cmd.exe /c $cmd
        if ($LASTEXITCODE -ne 0) { throw "SQL dump import fail hua (exit code $LASTEXITCODE)." }
        Write-Ok 'Dump import ho gaya'
    }
} else {
    Write-Host '    -SqlDump nahi diya - import skip (maan raha hoon DB pehle se bhari hai)' -ForegroundColor DarkGray
}

# ---------------------------------------------------- 5. autoload + cache --
Write-Step '5/7  Autoload regenerate + Laravel caches clear'
Invoke-Checked 'composer' @('dump-autoload','--no-interaction') 'composer dump-autoload'
Invoke-Checked 'php' @('artisan','optimize:clear','--ansi') 'optimize:clear'
Write-Ok 'Autoload + caches naye path ke hisaab se taiyaar'

# ------------------------------------------------------ 6. migrations -----
Write-Step '6/7  Migration status'
if ($WhatIfOnly) {
    Write-Dry 'php artisan migrate:status'
} else {
    $status = Get-NativeOutput 'php' @('artisan','migrate:status','--no-ansi')
    $pending = @($status -split "`n" | Where-Object { $_ -match '\bPending\b' })

    if ($LASTEXITCODE -ne 0) {
        Write-Warn 'migrate:status fail hua:'
        Write-Host $status -ForegroundColor DarkGray
    } elseif ($pending.Count -eq 0) {
        Write-Ok 'Saari migrations already Ran - DB schema up to date'
    } else {
        Write-Warn "$($pending.Count) pending migration(s) mili."
        if ($Migrate) {
            Invoke-Checked 'php' @('artisan','migrate','--force','--ansi') 'migrate'
            Write-Ok 'Pending migrations apply ho gayin'
        } else {
            Write-Warn "Apply karne ke liye: php artisan migrate     (ya script -Migrate ke saath chalao)"
        }
    }
}

# ------------------------------------------------------ 7. health ---------
Write-Step '7/7  Health check'
if ($WhatIfOnly) {
    Write-Dry 'php artisan about'
} else {
    $about = Get-NativeOutput 'php' @('artisan','about','--no-ansi')
    if ($LASTEXITCODE -ne 0) {
        Write-Err 'php artisan about fail hua:'
        Write-Host $about -ForegroundColor DarkGray
        if ($about -match 'Pdo\\Mysql') {
            Write-Host ''
            Write-Err 'Yahi wo PHP 8.4 wala issue hai - Laragon me PHP 8.4 select karo.'
        }
        exit 1
    }
    ($about -split "`n" | Where-Object { $_ -match 'PHP Version|Environment|Debug Mode|Database|Cache|Laravel Version' }) |
        ForEach-Object { Write-Host "    $($_.Trim())" -ForegroundColor DarkGray }

    $settingsCount = Get-NativeOutput 'mysql' ((Get-MysqlArgs) + @('-N','-B','-e',"USE ``$dbName``; SELECT COUNT(*) FROM settings;"))
    if ($LASTEXITCODE -eq 0) {
        $n = $settingsCount.Trim()
        $parsed = 0
        if ([int]::TryParse($n, [ref] $parsed) -and $parsed -gt 0) {
            Write-Ok "settings table me $n rows (site content maujood hai)"
        } else {
            Write-Warn "settings table khaali hai - site ka content nahi aaya. DB dump dobara check karo."
        }
    }
}

# ----------------------------------------------------------- summary ------
$url = if ($appUrl -ne '') { $appUrl } else { 'http://maverick.test' }

Write-Host ''
Write-Host '  ======================================================' -ForegroundColor Green
Write-Host ('   ' + $(if ($WhatIfOnly) { 'Dry run complete - kuch change nahi hua' } else { 'Migration complete!' })) -ForegroundColor Green
Write-Host '  ======================================================' -ForegroundColor Green
Write-Host ''
Write-Host "   Site    : $url"
Write-Host "   Admin   : $url/admin   (purane PC wale credentials)"
Write-Host "   Health  : $url/up      (200 aana chahiye)"
Write-Host ''
Write-Host '   Next:' -ForegroundColor Yellow
Write-Host '     - Laragon -> Reload (Run as Administrator)'
Write-Host '     - Admin password yaad na ho to: php artisan admin:create --email=<wahi email> --password=<naya>'
Write-Host ''
Write-Host '   Ye kabhi mat chalana: key:generate | composer update | migrate:fresh | db:seed' -ForegroundColor Red
Write-Host ''
Write-Host '   Guide: docs\WINDOWS_FOLDER_MIGRATION.md' -ForegroundColor DarkGray
Write-Host ''
