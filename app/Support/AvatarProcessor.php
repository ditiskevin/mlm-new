<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * Normalises an uploaded avatar: fixes EXIF orientation, center-crops to a
 * square and resizes to a consistent size, returning compact WebP bytes.
 */
class AvatarProcessor
{
    public const SIZE = 512;

    /**
     * @return string Binary WebP image data.
     */
    public static function toWebp(UploadedFile $file): string
    {
        $contents = file_get_contents($file->getRealPath());
        $src = @imagecreatefromstring($contents);

        if ($src === false) {
            throw new \RuntimeException('Kon de afbeelding niet verwerken.');
        }

        $src = self::applyExifOrientation($src, $file);

        $width = imagesx($src);
        $height = imagesy($src);
        $side = min($width, $height);
        $srcX = (int) (($width - $side) / 2);
        $srcY = (int) (($height - $side) / 2);

        $dst = imagecreatetruecolor(self::SIZE, self::SIZE);
        imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, self::SIZE, self::SIZE, $side, $side);

        ob_start();
        imagewebp($dst, null, 82);
        $binary = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $binary;
    }

    /**
     * Rotate JPEGs that carry an EXIF orientation flag (common from phones).
     */
    private static function applyExifOrientation(\GdImage $image, UploadedFile $file): \GdImage
    {
        if (! function_exists('exif_read_data') || ! in_array($file->getMimeType(), ['image/jpeg', 'image/tiff'], true)) {
            return $image;
        }

        $exif = @exif_read_data($file->getRealPath());
        $orientation = $exif['Orientation'] ?? null;

        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle !== 0) {
            $rotated = imagerotate($image, $angle, 0);
            if ($rotated !== false) {
                imagedestroy($image);

                return $rotated;
            }
        }

        return $image;
    }
}
