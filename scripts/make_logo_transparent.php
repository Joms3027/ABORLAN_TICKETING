<?php

ini_set('memory_limit', '512M');

$path = dirname(__DIR__) . '/public/images/Logo.png';

$im = @imagecreatefrompng($path);
if (! $im) {
    fwrite(STDERR, "Could not load: {$path}\n");
    exit(1);
}

$w = imagesx($im);
$h = imagesy($im);

$maxW = 1400;
if ($w > $maxW) {
    $newH = (int) round($h * ($maxW / $w));
    $scaled = imagescale($im, $maxW, $newH, IMG_BICUBIC);
    imagedestroy($im);
    if (! $scaled) {
        fwrite(STDERR, "Could not scale image.\n");
        exit(1);
    }
    $im = $scaled;
    $w = imagesx($im);
    $h = imagesy($im);
}

imagesavealpha($im, true);
imagealphablending($im, false);

$transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);

function pixelRgb($im, $x, $y): array
{
    $c = imagecolorat($im, $x, $y);

    return [
        ($c >> 16) & 0xFF,
        ($c >> 8) & 0xFF,
        $c & 0xFF,
    ];
}

function matchesBg(array $rgb, array $bg, int $tol): bool
{
    return abs($rgb[0] - $bg[0]) <= $tol
        && abs($rgb[1] - $bg[1]) <= $tol
        && abs($rgb[2] - $bg[2]) <= $tol;
}

function markBit(string &$bits, int $i): void
{
    $b = intdiv($i, 8);
    $m = 1 << ($i % 8);
    $bits[$b] = chr(ord($bits[$b]) | $m);
}

function testBit(string $bits, int $i): bool
{
    $b = intdiv($i, 8);
    $m = 1 << ($i % 8);

    return (ord($bits[$b]) & $m) !== 0;
}

$tolerance = 28;
$n = $w * $h;
$bitsLen = intdiv($n + 7, 8);
$visited = str_repeat("\0", $bitsLen);

$bgRgb = pixelRgb($im, 0, 0);
$stack = [];

$corners = [[0, 0], [$w - 1, 0], [0, $h - 1], [$w - 1, $h - 1]];
foreach ($corners as [$cx, $cy]) {
    if (! matchesBg(pixelRgb($im, $cx, $cy), $bgRgb, $tolerance)) {
        continue;
    }
    $idx = $cy * $w + $cx;
    if (! testBit($visited, $idx)) {
        markBit($visited, $idx);
        $stack[] = [$cx, $cy];
    }
}

while ($stack !== []) {
    [$x, $y] = array_pop($stack);
    imagesetpixel($im, $x, $y, $transparent);

    $neighbors = [[$x + 1, $y], [$x - 1, $y], [$x, $y + 1], [$x, $y - 1]];
    foreach ($neighbors as [$nx, $ny]) {
        if ($nx < 0 || $ny < 0 || $nx >= $w || $ny >= $h) {
            continue;
        }
        $nidx = $ny * $w + $nx;
        if (testBit($visited, $nidx)) {
            continue;
        }
        if (! matchesBg(pixelRgb($im, $nx, $ny), $bgRgb, $tolerance)) {
            continue;
        }
        markBit($visited, $nidx);
        $stack[] = [$nx, $ny];
    }
}

imagealphablending($im, false);
imagesavealpha($im, true);
imagepng($im, $path, 9);
imagedestroy($im);

echo "Updated {$path} with transparent background.\n";
