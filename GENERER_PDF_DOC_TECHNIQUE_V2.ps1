# Script PowerShell pour generer le PDF de la documentation technique
# Version amelioree avec conversion Markdown vers HTML complete

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  GENERATION PDF DOCUMENTATION TECHNIQUE" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$markdownFile = Join-Path $PSScriptRoot "DOCUMENTATION_TECHNIQUE.md"
$htmlFile = Join-Path $PSScriptRoot "DOCUMENTATION_TECHNIQUE.html"
$pdfFile = Join-Path $PSScriptRoot "Documentation_Technique_Montaza.pdf"

# Verifier si le fichier Markdown existe
if (-not (Test-Path $markdownFile)) {
    Write-Host "ERREUR: Le fichier DOCUMENTATION_TECHNIQUE.md n existe pas!" -ForegroundColor Red
    Read-Host "Appuyez sur Entree pour quitter"
    exit 1
}

Write-Host "Fichier Markdown trouve: $markdownFile" -ForegroundColor Green
Write-Host "Conversion du Markdown en HTML..." -ForegroundColor Yellow
Write-Host ""

# Lire le contenu Markdown
$markdownContent = Get-Content -Path $markdownFile -Raw -Encoding UTF8

# Fonction de conversion Markdown basique vers HTML
function Convert-MarkdownToHtml {
    param([string]$markdown)
    
    # Echapper les caracteres HTML
    $html = $markdown
    
    # Convertir les titres
    $html = $html -replace '(?m)^# ([^\n]+)', '<h1>$1</h1>'
    $html = $html -replace '(?m)^## ([^\n]+)', '<h2>$1</h2>'
    $html = $html -replace '(?m)^### ([^\n]+)', '<h3>$1</h3>'
    $html = $html -replace '(?m)^#### ([^\n]+)', '<h4>$1</h4>'
    
    # Convertir les lignes horizontales
    $html = $html -replace '(?m)^---$', '<hr>'
    
    # Convertir le gras et italique
    $html = $html -replace '\*\*([^\*]+)\*\*', '<strong>$1</strong>'
    $html = $html -replace '\*([^\*]+)\*', '<em>$1</em>'
    
    # Convertir le code inline
    $html = $html -replace '`([^`]+)`', '<code>$1</code>'
    
    # Convertir les blocs de code
    $html = $html -replace '(?s)```(\w+)?\n(.*?)```', '<pre><code>$2</code></pre>'
    
    # Convertir les listes non ordonnees
    $html = $html -replace '(?m)^\* (.+)$', '<li>$1</li>'
    $html = $html -replace '(?m)^- (.+)$', '<li>$1</li>'
    
    # Convertir les liens
    $html = $html -replace '\[([^\]]+)\]\(([^\)]+)\)', '<a href="$2">$1</a>'
    
    # Convertir les paragraphes (lignes vides)
    $html = $html -replace '(?m)^([^<\n][^\n]+)$', '<p>$1</p>'
    
    # Nettoyer les p dans les li
    $html = $html -replace '<li><p>([^<]+)</p></li>', '<li>$1</li>'
    
    # Grouper les listes
    $html = $html -replace '(<li>.*?</li>(?:\s*<li>.*?</li>)*)', '<ul>$1</ul>'
    
    return $html
}

# Creer le HTML complet avec styles
$htmlTemplate = @"
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation Technique - Montaza ERP</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-size: 11pt;
            background: white;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-top: 30px;
            page-break-before: always;
            font-size: 24pt;
            page-break-after: avoid;
        }
        h1:first-of-type {
            page-break-before: auto;
        }
        h2 {
            color: #34495e;
            border-bottom: 2px solid #95a5a6;
            padding-bottom: 8px;
            margin-top: 25px;
            font-size: 18pt;
            page-break-after: avoid;
        }
        h3 {
            color: #2980b9;
            margin-top: 20px;
            font-size: 14pt;
            page-break-after: avoid;
        }
        h4 {
            color: #16a085;
            margin-top: 15px;
            font-size: 12pt;
            page-break-after: avoid;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            color: #c7254e;
            word-break: break-word;
        }
        pre {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-left: 4px solid #3498db;
            padding: 15px;
            overflow-x: auto;
            border-radius: 4px;
            page-break-inside: avoid;
            margin: 15px 0;
        }
        pre code {
            background-color: transparent;
            padding: 0;
            color: #333;
            font-size: 8.5pt;
            display: block;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 15px 0;
            page-break-inside: avoid;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        blockquote {
            border-left: 4px solid #3498db;
            padding-left: 15px;
            margin-left: 0;
            color: #555;
            font-style: italic;
        }
        ul, ol {
            margin: 10px 0;
            padding-left: 30px;
        }
        li {
            margin: 5px 0;
        }
        hr {
            border: none;
            border-top: 2px solid #ecf0f1;
            margin: 30px 0;
            page-break-after: avoid;
        }
        strong {
            color: #2c3e50;
            font-weight: 600;
        }
        em {
            font-style: italic;
            color: #555;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        p {
            margin: 10px 0;
            text-align: justify;
        }
        .page-break {
            page-break-before: always;
        }
        @media print {
            body {
                font-size: 10pt;
                padding: 0;
            }
            h1 {
                font-size: 20pt;
            }
            h2 {
                font-size: 16pt;
            }
            h3 {
                font-size: 13pt;
            }
            h4 {
                font-size: 11pt;
            }
            pre, table {
                page-break-inside: avoid;
            }
            a {
                color: #3498db;
                text-decoration: none;
            }
        }
    </style>
</head>
<body>
CONTENT_PLACEHOLDER
</body>
</html>
"@

# Convertir le Markdown en HTML
$htmlBody = Convert-MarkdownToHtml -markdown $markdownContent

# Inserer dans le template
$htmlContent = $htmlTemplate -replace 'CONTENT_PLACEHOLDER', $htmlBody

# Ecrire le fichier HTML
[System.IO.File]::WriteAllText($htmlFile, $htmlContent, [System.Text.Encoding]::UTF8)

Write-Host "Fichier HTML cree: $htmlFile" -ForegroundColor Green
$htmlSize = (Get-Item $htmlFile).Length
Write-Host "Taille HTML: $([math]::Round($htmlSize / 1KB, 2)) KB" -ForegroundColor Gray
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

# Chercher Edge si Chrome n est pas trouve
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
    Write-Host "Navigateur trouve: $chrome" -ForegroundColor Green
    Write-Host "Generation du PDF en cours..." -ForegroundColor Yellow
    Write-Host "(Patientez 5-10 secondes...)" -ForegroundColor Gray
    Write-Host ""
    
    # Convertir le chemin du fichier en URI
    $htmlUri = "file:///$($htmlFile -replace '\\', '/')"
    
    # Supprimer l ancien PDF s il existe
    if (Test-Path $pdfFile) {
        Remove-Item $pdfFile -Force
    }
    
    # Commande pour generer le PDF avec Chrome/Edge en mode headless
    $arguments = @(
        "--headless=new",
        "--disable-gpu",
        "--no-sandbox",
        "--disable-dev-shm-usage",
        "--print-to-pdf=`"$pdfFile`"",
        "--print-to-pdf-no-header",
        "--no-pdf-header-footer",
        "`"$htmlUri`""
    )
    
    try {
        $process = Start-Process -FilePath $chrome -ArgumentList $arguments -Wait -NoNewWindow -PassThru
        
        # Attendre que le PDF soit cree
        $timeout = 15
        $elapsed = 0
        while (-not (Test-Path $pdfFile) -and $elapsed -lt $timeout) {
            Start-Sleep -Milliseconds 500
            $elapsed += 0.5
        }
        
        if (Test-Path $pdfFile) {
            $fileSize = (Get-Item $pdfFile).Length
            
            # Verifier que le PDF n est pas vide
            if ($fileSize -gt 1000) {
                Write-Host "========================================" -ForegroundColor Green
                Write-Host "PDF GENERE AVEC SUCCES!" -ForegroundColor Green
                Write-Host "========================================" -ForegroundColor Green
                Write-Host ""
                Write-Host "Fichier cree: $pdfFile" -ForegroundColor Cyan
                if ($fileSize -gt 1MB) {
                    Write-Host "Taille: $([math]::Round($fileSize / 1MB, 2)) MB" -ForegroundColor Gray
                } else {
                    Write-Host "Taille: $([math]::Round($fileSize / 1KB, 2)) KB" -ForegroundColor Gray
                }
                Write-Host ""
                
                # Demander si on veut ouvrir le PDF
                $open = Read-Host "Voulez-vous ouvrir le PDF maintenant? (O/N)"
                if ($open -eq "O" -or $open -eq "o") {
                    Start-Process $pdfFile
                }
                
                # Proposer de garder ou supprimer le HTML
                Write-Host ""
                $keepHtml = Read-Host "Voulez-vous conserver le fichier HTML? (O/N)"
                if ($keepHtml -ne "O" -and $keepHtml -ne "o") {
                    Remove-Item $htmlFile -Force
                    Write-Host "Fichier HTML temporaire supprime" -ForegroundColor Green
                } else {
                    Write-Host "Fichier HTML conserve: $htmlFile" -ForegroundColor Cyan
                }
            } else {
                Write-Host "ATTENTION: Le PDF genere semble vide ou corrompu (taille: $fileSize octets)" -ForegroundColor Red
                Write-Host "Ouverture du fichier HTML pour inspection..." -ForegroundColor Yellow
                Start-Process $htmlFile
            }
        } else {
            Write-Host "ERREUR: Le PDF n a pas ete cree apres $timeout secondes." -ForegroundColor Red
            Write-Host "Code de sortie du navigateur: $($process.ExitCode)" -ForegroundColor Yellow
            Write-Host "Ouverture du fichier HTML pour impression manuelle..." -ForegroundColor Yellow
            Start-Process $htmlFile
            Write-Host ""
            Write-Host "Utilisez Ctrl+P et selectionnez Enregistrer au format PDF" -ForegroundColor Yellow
        }
    } catch {
        Write-Host "ERREUR lors de la generation du PDF: $_" -ForegroundColor Red
        Write-Host "Ouverture du fichier HTML pour impression manuelle..." -ForegroundColor Yellow
        Start-Process $htmlFile
    }
} else {
    Write-Host "Chrome ou Edge n a pas ete trouve." -ForegroundColor Yellow
    Write-Host "Ouverture du fichier HTML dans le navigateur par defaut..." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "INSTRUCTIONS:" -ForegroundColor Cyan
    Write-Host "1. Le fichier HTML va s ouvrir dans votre navigateur" -ForegroundColor White
    Write-Host "2. Appuyez sur Ctrl+P pour imprimer" -ForegroundColor White
    Write-Host "3. Selectionnez Enregistrer au format PDF comme imprimante" -ForegroundColor White
    Write-Host "4. Enregistrez le fichier sous: Documentation_Technique_Montaza.pdf" -ForegroundColor White
    Write-Host ""
    
    Start-Process $htmlFile
}

Write-Host ""
Write-Host "Script termine." -ForegroundColor Cyan
Read-Host "Appuyez sur Entree pour quitter"
