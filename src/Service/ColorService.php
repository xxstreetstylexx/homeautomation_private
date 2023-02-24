<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

/**
 * Description of ColorService
 *
 * @author Carsten
 */
class ColorService {
   public static function RGBFromXYBri($x, $y, $brightness) {
        $Y = $brightness;
        $X = ($Y / $y) * $x;
        $Z = ($Y / $y) * (1 - $x - $y);
        $rgb = [
            $X * 1.612 - $Y * 0.203 - $Z * 0.302,
            -$X * 0.509 + $Y * 1.412 + $Z * 0.066,
            $X * 0.026 - $Y * 0.072 + $Z * 0.962
        ];

        $rgb = array_map(function ($x) {
            return ($x <= 0.0031308) ? (12.92 * $x) : ((1.0 + 0.055) * pow($x, (1.0 / 2.4)) - 0.055);
        }, $rgb);

        $rgb = array_map(function ($x) { return max(0, $x); }, $rgb);
        $max = max($rgb[0], $rgb[1], $rgb[2]);
        if ($max > 1)
            $rgb = array_map(function ($x) use($max) { return $x / $max; }, $rgb);

        $rgb = array_map(function ($x) { return $x * 255;}, $rgb);
        return [
            'r' => $rgb[0],
            'g' => $rgb[1],
            'b' => $rgb[2]
        ];
    }
    public function fromRGB($R, $G, $B)
    {

        $R = dechex($R);
        if (strlen($R)<2)
        $R = '0'.$R;

        $G = dechex($G);
        if (strlen($G)<2)
        $G = '0'.$G;

        $B = dechex($B);
        if (strlen($B)<2)
        $B = '0'.$B;

        return '#' . $R . $G . $B;
    }
}
