<?php

namespace Guym4c\DoctrineOAuth\Provider;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use RobThree\Auth\Providers\Qr\IQRCodeProvider;

class QrCodeProvider implements IQRCodeProvider {

    private const QR_MIME_TYPE = 'image/svg+xml';

    public function getQRCodeImage($qrtext, $size): string {
        return (new Writer(new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd())))
            ->writeString($qrtext);
    }

    public function getMimeType(): string {
        return self::QR_MIME_TYPE;
    }
}