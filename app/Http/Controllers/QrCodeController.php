<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Imagecow\Image;


class QrCodeController extends Controller
{
    public function generateAndDownloadQrCode(Request $request)
    {
        $url = 'https://techvblogs.com/blog/generate-qr-code-laravel-8';
        $qrCode = QrCode::size(300)->generate($url);

        // Generate a unique file name for the QR code
        $fileName = 'qrcode_' . time() . '.svg';

        // Save the QR code as an image file in the storage directory
        $path = public_path('qrcodes/' . $fileName);
        // die($path);
        file_put_contents($path, $qrCode);
        // Image::fromFile()

        $image = Image::fromFile($path);
        $image->format('png');

        // Define the output path for the PNG file
        $pngFilePath = public_path('png/output-filename.png');

        // Save the PNG image
        $image->save($pngFilePath);

        // Return a response with the path to the saved QR code image
        return response()->download($pngFilePath, $fileName);
    }
}
