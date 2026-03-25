# Script PowerShell pour générer automatiquement le PDF de la documentation Montaza
# Ce script utilise Chrome ou Edge en mode headless pour convertir HTML en PDF

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  GENERATION DU PDF DE DOCUMENTATION" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$htmlFile = Join-Path $PSScriptRoot "DOCUMENTATION_COMPLETE.html"
$pdfFile = Join-Path $PSScriptRoot "Documentation_Montaza.pdf"

# Vérifier si le fichier HTML existe
if (-not (Test-Path $htmlFile)) {
    Write-Host "ERREUR: Le fichier DOCUMENTATION_COMPLETE.html n'existe pas!" -ForegroundColor Red
    Read-Host "Appuyez sur Entrée pour quitter"
    exit 1
}

Write-Host "Fichier HTML trouvé: $htmlFile" -ForegroundColor Green
Write-Host ""

# Chercher Chrome
$chromePaths = @(
    "${env:ProgramFiles}\Google\Chrome\Application\chrome.exe",
    "${env:ProgramFiles(x86)}\Google\Chrome\Application\chrome.exe",
    "${env:LocalAppData}\Google\Chrome\Application\chrome.exe"
)

$chrome = $null
foreach ($path in $chromePaths) {
    if (Test-Path $path) {
        $chrome = $path
        break
    }
}

# Chercher Edge si Chrome n'est pas trouvé
if (-not $chrome) {
    $edgePaths = @(
        "${env:ProgramFiles}\Microsoft\Edge\Application\msedge.exe",
        "${env:ProgramFiles(x86)}\Microsoft\Edge\Application\msedge.exe"
    )
    
    foreach ($path in $edgePaths) {
        if (Test-Path $path) {
            $chrome = $path
            break
        }
    }
}

if ($chrome) {
    Write-Host "Navigateur trouvé: $chrome" -ForegroundColor Green
    Write-Host "Génération du PDF en cours..." -ForegroundColor Yellow
    Write-Host ""
    
    # Convertir le chemin du fichier en URI
    $htmlUri = "file:///$($htmlFile -replace '\\', '/')"
    
    # Commande pour générer le PDF avec Chrome/Edge en mode headless
    $arguments = @(
        "--headless",
        "--disable-gpu",
        "--print-to-pdf=`"$pdfFile`"",
        "--no-margins",
        "`"$htmlUri`""
    )
    
    try {
        Start-Process -FilePath $chrome -ArgumentList $arguments -Wait -NoNewWindow
        
        if (Test-Path $pdfFile) {
            Write-Host "✓ PDF généré avec succès!" -ForegroundColor Green
            Write-Host ""
            Write-Host "Fichier créé: $pdfFile" -ForegroundColor Cyan
            Write-Host "Taille: $((Get-Item $pdfFile).Length / 1KB) KB" -ForegroundColor Gray
            Write-Host ""
            
            # Demander si on veut ouvrir le PDF
            $open = Read-Host "Voulez-vous ouvrir le PDF maintenant? (O/N)"
            if ($open -eq "O" -or $open -eq "o") {
                Start-Process $pdfFile
            }
        } else {
            Write-Host "ERREUR: Le PDF n'a pas été créé." -ForegroundColor Red
            Write-Host "Tentative d'ouverture du fichier HTML dans le navigateur pour impression manuelle..." -ForegroundColor Yellow
            Start-Process $htmlFile
            Write-Host ""
            Write-Host "Utilisez Ctrl+P et sélectionnez 'Enregistrer au format PDF'" -ForegroundColor Yellow
        }
    } catch {
        Write-Host "ERREUR lors de la génération du PDF: $_" -ForegroundColor Red
        Write-Host "Ouverture du fichier HTML pour impression manuelle..." -ForegroundColor Yellow
        Start-Process $htmlFile
    }
} else {
    Write-Host "Chrome ou Edge n'a pas été trouvé." -ForegroundColor Yellow
    Write-Host "Ouverture du fichier HTML dans le navigateur par défaut..." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "INSTRUCTIONS:" -ForegroundColor Cyan
    Write-Host "1. Utilisez Ctrl+P pour ouvrir le dialogue d'impression" -ForegroundColor White
    Write-Host "2. Sélectionnez 'Enregistrer au format PDF' ou 'Microsoft Print to PDF'" -ForegroundColor White
    Write-Host "3. Enregistrez le fichier sous le nom: Documentation_Montaza.pdf" -ForegroundColor White
    Write-Host ""
    Write-Host "Alternative: Cliquez sur le bouton bleu 'Imprimer en PDF' en bas à droite" -ForegroundColor White
    Write-Host ""
    
    Start-Process $htmlFile
}

Write-Host ""
Read-Host "Appuyez sur Entrée pour quitter"
