<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Datasource\EntityInterface;

class DompdfController extends AppController
{
    /**
     * Serve generated PDF files from `webroot/dompdf`.
     */
    public function download(?string $file = null): Response
    {
        $this->autoRender = false;
        if ($file === null || $file === '') {
            throw new NotFoundException('Missing file.');
        }

        // Keep path traversal impossible while preserving spaces/encoded chars.
        $decoded = rawurldecode($file);
        $safeName = basename($decoded);
        if ($safeName === '' || strtolower(pathinfo($safeName, PATHINFO_EXTENSION)) !== 'pdf') {
            throw new NotFoundException('Invalid file.');
        }

        $path = WWW_ROOT . 'dompdf' . DS . $safeName;
        if (!is_file($path) && str_ends_with(strtolower($safeName), '.pdf')) {
            // Backward-compat for older rows generated without ".pdf" suffix.
            $legacyPath = WWW_ROOT . 'dompdf' . DS . substr($safeName, 0, -4);
            if (is_file($legacyPath)) {
                $path = $legacyPath;
            }
        }
        if (!is_file($path)) {
            // Regenerate missing PDF from Mailer row when possible (legacy rows).
            $this->regenerateFromMailerRow($safeName, $path);
        }
        if (!is_file($path)) {
            throw new NotFoundException('PDF not found.');
        }

        return $this->response->withFile($path, [
            'name' => $safeName,
            'download' => false,
        ]);
    }

    /**
     * Rebuild PDF from `mailer` table for filenames like "{id}-{track}.pdf".
     */
    private function regenerateFromMailerRow(string $safeName, string $targetPath): void
    {
        if (!preg_match('/^(\d+)-.*\.pdf$/i', $safeName, $m)) {
            return;
        }
        $id = (int)$m[1];
        if ($id <= 0) {
            return;
        }

        $row = $this->fetchTable('Mailer')->find()->where(['Mailer.id' => $id])->first();
        if (!$row instanceof EntityInterface) {
            return;
        }
        $mailer = $row->toArray();

        $fpdfPath = WWW_ROOT . 'pdf' . DS . 'FPDI' . DS . 'vendor' . DS . 'setasign' . DS . 'fpdf' . DS . 'fpdf.php';
        if (!is_file($fpdfPath)) {
            return;
        }
        require_once $fpdfPath;
        if (!class_exists('FPDF')) {
            return;
        }

        $dir = dirname($targetPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'DuroEzy Specification', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 8, 'Generated: ' . date('d/m/Y H:i'), 0, 1);
        $pdf->Ln(2);

        $lines = [
            ['Name/Attn', (string)($mailer['name'] ?? '')],
            ['Email', (string)($mailer['email'] ?? '')],
            ['Company', (string)($mailer['company'] ?? '')],
            ['Address', (string)($mailer['address'] ?? '')],
            ['Specification', (string)($mailer['specification'] ?? '')],
            ['Date', (string)($mailer['date'] ?? '')],
            ['Type', (string)($mailer['type'] ?? '')],
            ['Subject', (string)($mailer['subject'] ?? '')],
        ];
        foreach ($lines as [$label, $value]) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(40, 7, $label . ':', 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(150, 7, $value, 0, 1);
        }

        $pdf->Output('F', $targetPath);
    }
}

