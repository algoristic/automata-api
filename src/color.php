<?php

class Color
{
    private int $r;
    private int $g;
    private int $b;

    function __construct(int $r, int $g, int $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    function to_image_color(GdImage $image): int
    {
        $image_color = imagecolorallocate($image, $this->r, $this->g, $this->b);
        if ($image_color === false) {
            throw new Exception('Malformed color input', 500);
        }
        return $image_color;
    }

    function equals(Color $other): bool
    {
        return
            $this->r === $other->r &&
            $this->g === $other->g &&
            $this->b === $other->b;
    }

    static function from_hex(string $hex_code): Color
    {
        $hex_value = ltrim($hex_code, '#');
        $r = hexdec(substr($hex_value, 0, 2));
        $g = hexdec(substr($hex_value, 2, 2));
        $b = hexdec(substr($hex_value, 4, 2));
        return new Color($r, $g, $b);
    }

    static function lerp(Color $a, Color $b, float $t): Color
    {
        return new Color(
            $a->r + ($b->r - $a->r) * $t,
            $a->g + ($b->g - $a->g) * $t,
            $a->b + ($b->b - $a->b) * $t
        );
    }
}