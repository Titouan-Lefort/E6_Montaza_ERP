<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$source = __DIR__ . '/DOCUMENTATION_TECHNIQUE_REFAITE.md';
$output = __DIR__ . '/DOCUMENTATION_TECHNIQUE_REFAITE.pdf';

if (!file_exists($source)) {
    fwrite(STDERR, "Source Markdown introuvable: $source\n");
    exit(1);
}

$text = file_get_contents($source);

function markdownToHtml(string $text): string {
    $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $text = preg_replace('/```php\n(.*?)```/s', '<pre><code>$1</code></pre>', $text);
    $text = preg_replace('/```\n(.*?)```/s', '<pre><code>$1</code></pre>', $text);
    $text = preg_replace('/^###\s+(.*)$/m', '<h3>$1</h3>', $text);
    $text = preg_replace('/^##\s+(.*)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^#\s+(.*)$/m', '<h1>$1</h1>', $text);
    $text = preg_replace('/^>\s?(.*)$/m', '<blockquote>$1</blockquote>', $text);
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);

    $lines = explode("\n", $text);
    $html = '';
    $inList = false;

    foreach ($lines as $line) {
        if (preg_match('/^\s*[-*]\s+(.*)$/', $line, $matches)) {
            if (!$inList) {
                $html .= '<ul>';
                $inList = true;
            }
            $html .= '<li>' . $matches[1] . '</li>';
            continue;
        }

        if ($inList) {
            $html .= '</ul>';
            $inList = false;
        }

        if (trim($line) === '') {
            $html .= '<p></p>';
            continue;
        }

        if (preg_match('/^<(h[1-3]|blockquote|pre)>/i', $line)) {
            $html .= $line;
            continue;
        }

        $html .= '<p>' . $line . '</p>';
    }

    if ($inList) {
        $html .= '</ul>';
    }

    return '<div class="markdown">' . $html . '</div>';
}

$htmlContent = markdownToHtml($text);
$css = <<<CSS
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; margin: 24px; }
h1 { font-size: 24px; margin-top: 24px; }
h2 { font-size: 20px; margin-top: 20px; }
h3 { font-size: 16px; margin-top: 18px; }
pre { background: #f6f8fa; padding: 12px; border: 1px solid #ddd; overflow-x: auto; }
code { font-family: Consolas, monospace; font-size: 11px; }
blockquote { border-left: 4px solid #ccc; padding-left: 12px; color: #555; margin: 12px 0; }
ul { margin: 12px 0 12px 20px; }
strong { font-weight: bold; }
.markdown p { margin: 8px 0; }
.markdown { max-width: 800px; }
CSS;

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isRemoteEnabled', false);

$dompdf = new Dompdf($options);
$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><style>' . $css . '</style></head><body>' . $htmlContent . '</body></html>';
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

file_put_contents($output, $dompdf->output());

fwrite(STDOUT, "PDF généré: $output\n");
