<?php

namespace App\Services;

use FPDF;
use setasign\Fpdi\Fpdi;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentSignatureService
{
    public function addSignatureToDocument($filePath, $outputPath)
    {
        try {
            // Pastikan direktori output ada
            $outputDir = dirname($outputPath);
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0775, true);
            }

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            if ($extension === 'pdf') {
                return $this->addSignatureToPdf($filePath, $outputPath);
            } elseif (in_array($extension, ['doc', 'docx'])) {
                return $this->addSignatureToWord($filePath, $outputPath);
            }
            
            throw new \Exception('Format file tidak didukung');
        } catch (\Exception $e) {
            Log::error('Error adding signature: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function addSignatureToPdf($filePath, $outputPath)
    {
        try {
            // Pastikan file asli ada
            if (!file_exists($filePath)) {
                throw new \Exception('File asli tidak ditemukan: ' . $filePath);
            }

            // Inisialisasi FPDI
            $pdf = new Fpdi();
            
            // Hitung jumlah halaman
            $pageCount = $pdf->setSourceFile($filePath);
            
            // Import semua halaman
            for ($i = 1; $i <= $pageCount; $i++) {
                $template = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($template);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($template);
                
                // Tambahkan tanda tangan di halaman terakhir
                if ($i === $pageCount) {
                    $signaturePath = public_path('img/profile-default.png');
                    if (file_exists($signaturePath)) {
                        // Hitung posisi tanda tangan (pojok kanan bawah)
                        $x = $size['width'] - 60; // 60 points dari kanan
                        $y = $size['height'] - 60; // 60 points dari bawah
                        $width = 50; // Lebar tanda tangan
                        
                        $pdf->Image($signaturePath, $x, $y, $width);
                        
                        // Tambahkan teks di bawah tanda tangan
                        $pdf->SetFont('Arial', '', 10);
                        $pdf->SetXY($x, $y + $width + 5);
                        $pdf->Cell($width, 5, 'Ditandatangani oleh Admin', 0, 1, 'C');
                    } else {
                        Log::error('Signature file not found at: ' . $signaturePath);
                        throw new \Exception('File tanda tangan tidak ditemukan');
                    }
                }
            }
            
            // Simpan file
            $pdf->Output($outputPath, 'F');
            
            return $outputPath;
        } catch (\Exception $e) {
            Log::error('Error in addSignatureToPdf: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function addSignatureToWord($filePath, $outputPath)
    {
        try {
            // Pastikan file asli ada
            if (!file_exists($filePath)) {
                throw new \Exception('File asli tidak ditemukan: ' . $filePath);
            }

            $phpWord = IOFactory::load($filePath);
            
            // Tambahkan tanda tangan di halaman terakhir
            $lastSection = $phpWord->getSections()->last();
            
            // Tambahkan paragraf kosong untuk spacing
            $lastSection->addText('');
            
            // Tambahkan tanda tangan
            $signaturePath = public_path('img/profile-default.png');
            if (file_exists($signaturePath)) {
                $lastSection->addImage(
                    $signaturePath,
                    [
                        'width' => 100,
                        'height' => 100,
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT
                    ]
                );
                
                // Tambahkan teks di bawah tanda tangan
                $lastSection->addText(
                    'Ditandatangani oleh Admin',
                    ['bold' => true],
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
                );
            } else {
                Log::error('Signature file not found at: ' . $signaturePath);
                throw new \Exception('File tanda tangan tidak ditemukan');
            }
            
            // Simpan file
            $phpWord->save($outputPath);
            
            return $outputPath;
        } catch (\Exception $e) {
            Log::error('Error in addSignatureToWord: ' . $e->getMessage());
            throw $e;
        }
    }
} 