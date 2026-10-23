<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class DocumentPdfService
{
    /**
     * Locate Google Chrome or Chromium executable on the system.
     */
    public static function getChromeBinary(): ?string
    {
        $binaries = [
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
            '/snap/bin/chromium',
        ];

        foreach ($binaries as $bin) {
            if (is_executable($bin)) {
                return $bin;
            }
        }

        $which = trim((string) shell_exec('which google-chrome 2>/dev/null || which chromium 2>/dev/null'));
        if (! empty($which) && is_executable($which)) {
            return $which;
        }

        return null;
    }

    /**
     * Generate PDF response using Headless Chrome (exact native Arabic rendering matching Moemen Team layout).
     * Falls back to DomPDF if Chrome is not available.
     * If $asDownload is false or format=html, returns the printable HTML response.
     */
    public static function render(string $view, array $data, string $filename, bool $asDownload = true): Response|BinaryFileResponse
    {
        $html = View::make($view, array_merge($data, [
            'isPdfMode' => true,
        ]))->render();

        $chromeBin = static::getChromeBinary();

        if ($chromeBin && function_exists('exec') && ! str_contains((string) ini_get('disable_functions'), 'exec')) {
            $tempHtml = tempnam(sys_get_temp_dir(), 'doc_html_').'.html';
            $tempPdf = tempnam(sys_get_temp_dir(), 'doc_pdf_').'.pdf';

            file_put_contents($tempHtml, $html);

            $cmd = sprintf(
                '%s --headless --disable-gpu --no-sandbox --no-pdf-header-footer --print-to-pdf=%s %s 2>&1',
                escapeshellcmd($chromeBin),
                escapeshellarg($tempPdf),
                escapeshellarg('file://'.$tempHtml)
            );

            exec($cmd, $output, $returnCode);

            if (file_exists($tempHtml)) {
                @unlink($tempHtml);
            }

            if ($returnCode === 0 && file_exists($tempPdf) && filesize($tempPdf) > 0) {
                $pdfContent = file_get_contents($tempPdf);
                @unlink($tempPdf);

                $disposition = $asDownload ? 'attachment' : 'inline';

                return response($pdfContent, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
                    'Content-Length' => strlen($pdfContent),
                ]);
            }

            if (file_exists($tempPdf)) {
                @unlink($tempPdf);
            }
        }

        // Fallback: If Chrome is unavailable, use Barryvdh DomPDF
        try {
            $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

            return $asDownload ? $pdf->download($filename) : $pdf->stream($filename);
        } catch (Throwable) {
            // Ultimate fallback: return clean printable HTML
            return response($html, 200, [
                'Content-Type' => 'text/html; charset=utf-8',
            ]);
        }
    }
}
