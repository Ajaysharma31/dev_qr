<?php

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Response;


if (!function_exists('generateAndDownloadQrCode')) {
    function generateAndDownloadQrCode()
    {
        $url = 'Hello World!'; // The content you want to encode in the QR code

        // Create an ImageRenderer
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );

        // Create a Writer
        $writer = new Writer($renderer);

        // Generate the QR code and save it to a file
        $qrCodePath = public_path('qrcode.png'); // Save the QR code in the public directory
        $writer->writeFile($url, $qrCodePath);

        // Create a response for downloading the QR code
        $response = new Response(file_get_contents($qrCodePath), 200);
        $response->header('Content-Type', 'image/png');
        $response->header('Content-Disposition', 'attachment; filename="qrcode.png"');

        return $response;
    }
}
