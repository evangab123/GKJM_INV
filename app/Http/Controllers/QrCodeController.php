<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeController extends Controller
{
    public function generateQrCode($kode_barang)
    {
        // Create QR Code content
        $content = route('barang.show', $kode_barang);

        // Define the path to save the QR code image in the public directory
        $fileName = 'qrcode-' . $kode_barang . '.png';
        $filePath = public_path('img/qr/' . $fileName);
        // Check if the QR code file already exists
        if (!file_exists($filePath)) {
            // Create the QR code
            $renderer = new ImageRenderer(
                new RendererStyle(400),
                new ImagickImageBackEnd()
            );

            $writer = new Writer($renderer);
            $writer->writeFile($content, $filePath);
        }

        // Return the URL to access the QR code image
        return asset('img/qr/' . $fileName);
    }
}
