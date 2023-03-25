<?php

namespace Digueloulou12\CustomBox\API;

class SkinAPI
{
    public static function pngToBytes(string $path): string
    {
        $img = @imagecreatefrompng($path);
        $bytes = "";
        for ($y = 0; $y < (int)@getimagesize($path)[1]; $y++) {
            for ($x = 0; $x < (int)@getimagesize($path)[0]; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $bytes .= chr(($rgba >> 16) & 0xff) . chr(($rgba >> 8) & 0xff) . chr($rgba & 0xff) . chr(((~((int)($rgba >> 24))) << 1) & 0xff);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }
}