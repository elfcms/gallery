<?php

namespace Elfcms\Gallery\Elf;

use Illuminate\Support\Facades\Storage;

class Image {

    public static function resize(string $file, string $destination, int $width = null, int $height = null)
    {
        $filePath = Storage::path($file);
        if (empty($file) || empty($filePath) || !file_exists($filePath)) {
            return false;
        }
        $imageData = getimagesize($filePath);
        if (!in_array($imageData[2],[1,2,3,18])) {
            return false;
        }
        $imageWidth = $imageData[0];
        $imageHeight = $imageData[1];
        $imageKoef = $imageWidth / $imageHeight;

        $dstX = 0;
        $dstY = 0;

        $tmpWidth = $width;
        $tmpHeight = $height;

        if (empty($width) && empty($height)) {
            $width = $tmpWidth = $imageWidth;
            $height = $tmpHeight = $imageHeight;
        }
        elseif (empty($width)) {
            $width = $tmpWidth = $imageWidth / ($imageHeight / $height);
        }
        elseif (empty($height)) {
            $height = $tmpHeight = $imageHeight / ($imageWidth / $width);
        }
        else {
            $koef = $width / $height;
            if ($imageKoef < $koef) {
                $tmpWidth = round($height * $imageKoef);
                $dstX = round(abs($width - $tmpWidth) / 2);
            }
            elseif ($imageKoef > $koef) {
                $tmpHeight = round($width / $imageKoef);
                $dstY = round(abs($height - $tmpHeight) / 2);
            }
        }
        $extension = image_type_to_extension($imageData[2],false);
        $crateFunction = 'imagecreatefrom' . $extension;
        $saveFunction = 'image' . $extension;
        $image = $crateFunction($filePath);
        $tmpImage = imagecreatetruecolor($tmpWidth,$tmpHeight);
        if ($imageData[2] == 1 || $imageData[2] == 3 || $imageData[2] == 18) {
            imagealphablending($tmpImage, true);
            imagesavealpha($tmpImage, true);
            $transparent = imagecolorallocatealpha($tmpImage, 0, 0, 0, 127);
            imagefill($tmpImage, 0, 0, $transparent);
            imagecolortransparent($tmpImage, $transparent);
        }
        imagecopyresampled($tmpImage, $image, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $imageWidth, $imageHeight);
        $resultImage = imagecreatetruecolor($width,$height);
        if ($imageData[2] == 1 || $imageData[2] == 3 || $imageData[2] == 18) {
            imagealphablending($resultImage, true);
            imagesavealpha($resultImage, true);
            $transparent = imagecolorallocatealpha($resultImage, 0, 0, 0, 127);
            imagefill($resultImage, 0, 0, $transparent);
            imagecolortransparent($resultImage, $transparent);
        }
        else {
            $white = imagecolorallocate($resultImage, 255, 255, 255);
            imagefill($resultImage, 0, 0, $white);
        }

        imagecopy($resultImage, $tmpImage, $dstX, $dstY, 0, 0, $tmpWidth, $tmpHeight);

        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }

        $resultName = basename($filePath,'.'.$extension) . '_' . $width . '_' . $height . '.' . $extension;
        $resultPath = Storage::path($destination) . $resultName;
        $result = $saveFunction($resultImage,$resultPath);

        imagedestroy($tmpImage);
        imagedestroy($image);
        imagedestroy($resultImage);

        if ($result) {
            $result = $destination . $resultName;
        }

        return $result;
    }

}
