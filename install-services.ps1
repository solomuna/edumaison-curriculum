# install-services.ps1
# Installe nginx et PostgreSQL comme services Windows (auto-start) pour EduMaison.
# A lancer une seule fois, en PowerShell ADMIN. Idempotent.
#
# Apres install : Laragon n'a plus a etre lance pour qu'EduMaison fonctionne.
# Pour controler les services : services.msc (ou sc start/stop nginx / postgresql).

#Requires -RunAsAdministrator

$ErrorActionPreference = 'Continue'
$NGINX_DIR = 'C:\laragon\bin\nginx\nginx-1.27.3'
$NGINX_EXE = Join-Path $NGINX_DIR 'nginx.exe'
$PG_BIN    = 'C:\laragon\bin\postgresql\postgresql\bin'
$PG_CTL    = Join-Path $PG_BIN 'pg_ctl.exe'
$PG_DATA   = 'C:\laragon\data\postgresql'
$NSSM      = 'C:\nssm\nssm.exe'
$LARAGON_INI = 'C:\laragon\usr\laragon.ini'

Write-Host "`n=== 1. Arret des processus actuels ===" -ForegroundColor Cyan
Get-Process nginx -ErrorAction SilentlyContinue | ForEach-Object {
    Write-Host "  Stop nginx PID $($_.Id)"
    Stop-Process -Id $_.Id -Force -ErrorAction SilentlyContinue
}
if (Test-Path $PG_CTL) {
    & $PG_CTL -D $PG_DATA stop -m fast 2>&1 | Out-Null
    Start-Sleep -Seconds 2
}
Get-Process postgres -ErrorAction SilentlyContinue | ForEach-Object {
    Write-Host "  Stop postgres PID $($_.Id)"
    Stop-Process -Id $_.Id -Force -ErrorAction SilentlyContinue
}
Start-Sleep -Seconds 2

Write-Host "`n=== 2. Service PostgreSQL ===" -ForegroundColor Cyan
$pgService = Get-Service -Name 'postgresql' -ErrorAction SilentlyContinue
if ($pgService) {
    Write-Host "  Deja installe ($($pgService.Status)) — saute"
} else {
    Write-Host "  Enregistrement via pg_ctl register..."
    & $PG_CTL register -N 'postgresql' -D $PG_DATA -S auto 2>&1
    # ACL : donner acces a LocalSystem sur le data dir (au cas ou il n'a pas heritage)
    icacls $PG_DATA /grant 'NT AUTHORITY\SYSTEM:(OI)(CI)F' /T /Q 2>&1 | Out-Null
}

Write-Host "`n=== 3. Service nginx (via NSSM) ===" -ForegroundColor Cyan
$nginxService = Get-Service -Name 'nginx' -ErrorAction SilentlyContinue
if ($nginxService) {
    Write-Host "  Deja installe ($($nginxService.Status)) — saute"
} else {
    if (-not (Test-Path $NSSM)) { throw "NSSM introuvable a $NSSM" }
    Write-Host "  Installation via NSSM..."
    & $NSSM install nginx $NGINX_EXE | Out-Null
    & $NSSM set nginx AppDirectory $NGINX_DIR | Out-Null
    & $NSSM set nginx Start SERVICE_AUTO_START | Out-Null
    & $NSSM set nginx AppStdout (Join-Path $NGINX_DIR 'logs\nssm-stdout.log') | Out-Null
    & $NSSM set nginx AppStderr (Join-Path $NGINX_DIR 'logs\nssm-stderr.log') | Out-Null
    & $NSSM set nginx AppExit Default Restart | Out-Null
    & $NSSM set nginx AppThrottle 1500 | Out-Null
    & $NSSM set nginx Description 'nginx web server (EduMaison)' | Out-Null
}

Write-Host "`n=== 4. Desactiver auto-start de Laragon pour nginx/postgres ===" -ForegroundColor Cyan
if (Test-Path $LARAGON_INI) {
    Copy-Item $LARAGON_INI "$LARAGON_INI.bak-$(Get-Date -Format yyyyMMddHHmmss)"
    $ini = Get-Content $LARAGON_INI -Raw
    # Dans [nginx] et [postgresql], passer Use=-1 a Use=0 (Laragon ne touche plus)
    $new = $ini -replace '(?ms)(\[nginx\][^\[]*?\bUse=)-1','$10'
    $new = $new -replace '(?ms)(\[postgresql\][^\[]*?\bUse=)-1','$10'
    if ($new -ne $ini) {
        Set-Content -Path $LARAGON_INI -Value $new -NoNewline -Encoding ASCII
        Write-Host "  laragon.ini mis a jour (nginx + postgresql : Use=0). Backup .bak cree."
    } else {
        Write-Host "  laragon.ini deja a jour (rien a changer)"
    }
} else {
    Write-Host "  laragon.ini introuvable (skip)"
}

Write-Host "`n=== 5. Demarrage des services ===" -ForegroundColor Cyan
Start-Service postgresql -ErrorAction Continue
Start-Sleep -Seconds 3
Start-Service nginx -ErrorAction Continue
Start-Sleep -Seconds 2

Write-Host "`n=== 6. Verification ===" -ForegroundColor Cyan
Get-Service postgresql, nginx, EduMaisonAPI | Format-Table Name, Status, StartType -AutoSize
try {
    $code = (Invoke-WebRequest -Uri 'http://msi-laptop.local/app' -UseBasicParsing -TimeoutSec 5).StatusCode
    Write-Host "  http://msi-laptop.local/app -> $code" -ForegroundColor Green
} catch {
    Write-Host "  http://msi-laptop.local/app -> ECHEC : $($_.Exception.Message)" -ForegroundColor Yellow
}
try {
    $r = Invoke-WebRequest -Uri 'http://msi-laptop.local/api/children' -UseBasicParsing -TimeoutSec 5
    Write-Host "  /api/children -> $($r.StatusCode) ($(($r.Content.Substring(0,[Math]::Min(60,$r.Content.Length))))...)" -ForegroundColor Green
} catch {
    Write-Host "  /api/children -> ECHEC : $($_.Exception.Message)" -ForegroundColor Yellow
}

Write-Host "`nTermine. Les services demarreront automatiquement au prochain boot." -ForegroundColor Green
Write-Host "Pour annuler tout : sc.exe delete nginx ; sc.exe delete postgresql (en admin)" -ForegroundColor DarkGray
