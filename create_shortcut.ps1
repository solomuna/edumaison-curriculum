# Crée un raccourci EduMaison sur le bureau
$WshShell = New-Object -comObject WScript.Shell
$Desktop = [System.Environment]::GetFolderPath("Desktop")
$Shortcut = $WshShell.CreateShortcut("$Desktop\EduMaison.lnk")

# Cherche Chrome ou Edge
$Chrome = "C:\Program Files\Google\Chrome\Application\chrome.exe"
$Edge = "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

if (Test-Path $Chrome) {
    $Shortcut.TargetPath = $Chrome
    $Shortcut.Arguments = "--app=https://edumaison.test/app --window-size=414,896"
} elseif (Test-Path $Edge) {
    $Shortcut.TargetPath = $Edge
    $Shortcut.Arguments = "--app=https://edumaison.test/app --window-size=414,896"
} else {
    Write-Host "Chrome ou Edge non trouvé - raccourci non créé"
    exit
}

$Shortcut.WorkingDirectory = "C:\laragon\www\edumaison"
$Shortcut.WindowStyle = 1
$Shortcut.Description = "EduMaison - Plateforme éducative"
$Shortcut.Save()

Write-Host "Raccourci EduMaison créé sur le bureau !"