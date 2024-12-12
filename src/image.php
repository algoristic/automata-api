<?php

include_once __DIR__ . '/automaton.php';
include_once __DIR__ . '/color.php';

class Image
{
    private GdImage $image;
    private int $scale;
    private Color $alive_color;
    private Color $alive_interpolate_color;
    private Color $dead_color;
    private Color $dead_interpolate_color;

    function __construct(
        int $size,
        int $generations,
        int $scale,
        string $alive_hex,
        string $alive_interpolate_hex,
        string $dead_hex,
        string $dead_interpolate_hex
    ) {
        $this->image = imagecreatetruecolor($size * $scale, $generations * $scale);
        $this->scale = $scale;
        $this->alive_color = Color::from_hex($alive_hex);
        $this->alive_interpolate_color = Color::from_hex($alive_interpolate_hex);
        $this->dead_color = Color::from_hex($dead_hex);
        $this->dead_interpolate_color = Color::from_hex($dead_interpolate_hex);
    }

    function __destruct()
    {
        imagedestroy($this->image);
    }

    function render(Automaton $automaton, string $filetype): void
    {
        $generations = $automaton->grid;
        $k = $this->scale;
        $image = $this->image;
        $steps = count($generations);

        for ($gen = 0; $gen < $steps; $gen++) {
            $cells = $generations[$gen];
            $t = $gen / ($steps - 1);
            for ($i = 0; $i < count($cells); $i++) {
                $cell = $cells[$i];
                $x_1 = $i * $k;
                $x_2 = $x_1 + $k;
                $y_1 = $gen * $k;
                $y_2 = $y_1 + $k;
                if ($cell === 1) {
                    $color_a = $this->alive_color;
                    $color_b = $this->alive_interpolate_color;
                } else {
                    $color_a = $this->dead_color;
                    $color_b = $this->dead_interpolate_color;
                }
                $color = Color::lerp($color_a, $color_b, $t);
                $true_color = $color->to_image_color($image);
                imagefilledrectangle($image, $x_1, $y_1, $x_2, $y_2, $true_color);
            }
        }

        http_response_code(200);
        if ($filetype === 'png') {
            header('Content-Type: image/png');
            imagepng($image);
        }
        if ($filetype === 'jpg' || $filetype === 'jpeg') {
            header('Content-Type: image/jpeg');
            imagejpeg($image);
        }
        if ($filetype === 'gif') {
            header('Content-Type: image/gif');
            imagegif($image);
        }
        if ($filetype === 'webp') {
            header('Content-Type: image/webp');
            imagewebp($image);
        }
    }
}
