<?php

namespace App\Helpers;

use Spatie\PdfToImage\Pdf;

class PdfToImageHelper
{
    /**
     * Convert PDF to image and save each page as an image.
     *
     * @param string $pdfPath
     * @param string $outputDir
     * @return array
     */
    public static function convertPdfToImages($pdfPath, $outputDir)
    {
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $pdf = new Pdf($pdfPath);
        $totalPages = $pdf->getNumberOfPages();
        
        $imagePaths = [];
        
        for ($page = 1; $page <= $totalPages; $page++) {
            $imagePath = $outputDir . 'page_' . $page . '.jpg';
            $pdf->setPage($page)
                ->saveImage($imagePath);
                
            $imagePaths[] = $imagePath;
        }
        
        return $imagePaths;
    }
}
