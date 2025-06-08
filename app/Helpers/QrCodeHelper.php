<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class QrCodeHelper
{
    /**
     * Generate and save the QR code for a student.
     *
     * @param string $qrCodeData
     * @param string $nameFile
     * @return string The path to the saved QR code image.
     */
    public static function generateQrCode($qrCodeData, $nameFile)
    {
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrCodeData);
        
        $qrCodeResponse = Http::get($qrCodeUrl);

        $qrCodePath = 'qr_codes/' . $nameFile . '.webp';

        $qrCodeDirectory = public_path('qr_codes');

        if (!file_exists($qrCodeDirectory)) {
            mkdir($qrCodeDirectory, 0755, true);
        }

        if (file_exists(public_path($qrCodePath))) {
            file_put_contents(public_path($qrCodePath), $qrCodeResponse->body());
        } else {
            file_put_contents(public_path($qrCodePath), $qrCodeResponse->body());
        }

        return $qrCodePath;
    }
}
