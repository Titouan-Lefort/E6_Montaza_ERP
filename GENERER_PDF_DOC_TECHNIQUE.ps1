# Script PowerShell pour generer le PDF de la documentation technique
# Ce script convertit le Markdown en HTML puis genere un PDF

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
Write-Host ""

# Lire le contenu Markdown
$markdownContent = Get-Content -Path $markdownFile -Raw -Encoding UTF8

# Creer le HTML avec styles
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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-size: 11pt;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-top: 30px;
            page-break-before: always;
            font-size: 24pt;
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
        }
        h3 {
            color: #2980b9;
            margin-top: 20px;
            font-size: 14pt;
        }
        h4 {
            color: #16a085;
            margin-top: 15px;
            font-size: 12pt;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            color: #c7254e;
        }
        pre {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-left: 4px solid #3498db;
            padding: 15px;
            overflow-x: auto;
            border-radius: 4px;
            page-break-inside: avoid;
        }
        pre code {
            background-color: transparent;
            padding: 0;
            color: #333;
            font-size: 9pt;
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
            padding: 10px;
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
        }
        .toc {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        strong {
            color: #2c3e50;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media print {
            body {
                font-size: 10pt;
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
            pre, table, .toc {
                page-break-inside: avoid;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>
<body>
    <div id="content"></div>
    <script>
        const markdown = `MARKDOWN_CONTENT_PLACEHOLDER`;
        document.getElementById('content').innerHTML = marked.parse(markdown);
    </script>
</body>
</html>
"@

# Echapper le contenu Markdown pour JavaScript (remplacer les backticks et backslashes)
$escapedMarkdown = $markdownContent -replace '\\', '\\' -replace '`', '\`' -replace '\$', '\$'

# Inserer le contenu Markdown dans le template
$htmlContent = $htmlTemplate -replace 'MARKDOWN_CONTENT_PLACEHOLDER', $escapedMarkdown

# Ecrire le fichier HTML
[System.IO.File]::WriteAllText($htmlFile, $htmlContent, [System.Text.Encoding]::UTF8)

Write-Host "Fichier HTML cree: $htmlFile" -ForegroundColor Green
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
    Write-Host "(Cela peut prendre quelques secondes...)" -ForegroundColor Gray
    Write-Host ""
    
    # Convertir le chemin du fichier en URI
    $htmlUri = "file:///$($htmlFile -replace '\\', '/')"
    
    # Commande pour generer le PDF avec Chrome/Edge en mode headless
    $arguments = @(
        "--headless",
        "--disable-gpu",
        "--print-to-pdf=`"$pdfFile`"",
        "--print-to-pdf-no-header",
        "`"$htmlUri`""
    )
    
    try {
        Start-Process -FilePath $chrome -ArgumentList $arguments -Wait -NoNewWindow
        
        # Attendre un peu pour etre sur que le fichier est cree
        Start-Sleep -Seconds 2
        
        if (Test-Path $pdfFile) {
            Write-Host "========================================" -ForegroundColor Green
            Write-Host "PDF GENERE AVEC SUCCES!" -ForegroundColor Green
            Write-Host "========================================" -ForegroundColor Green
            Write-Host ""
            Write-Host "Fichier cree: $pdfFile" -ForegroundColor Cyan
            $fileSize = (Get-Item $pdfFile).Length
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
            
            # Demander si on veut supprimer le fichier HTML temporaire
            Write-Host ""
            $delete = Read-Host "Voulez-vous supprimer le fichier HTML temporaire? (O/N)"
            if ($delete -eq "O" -or $delete -eq "o") {
                Remove-Item $htmlFile -Force
                Write-Host "Fichier HTML temporaire supprime" -ForegroundColor Green
            }
        } else {
            Write-Host "ERREUR: Le PDF n a pas ete cree." -ForegroundColor Red
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
    Write-Host "4. Enregistrez le fichier" -ForegroundColor White
    Write-Host ""
    
    Start-Process $htmlFile
}

Write-Host ""
Write-Host "Script termine." -ForegroundColor Cyan
Read-Host "Appuyez sur Entree pour quitter"
