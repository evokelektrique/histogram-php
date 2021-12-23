<?php
// Histogram PHP

// Image inputs
$sources = ["./temp/test1.png", "./temp/test.jpg"];

// Loop through sources
foreach ($sources as $source) {
  $image = null;

  // Create PNG image
  if(exif_imagetype($source) === IMAGETYPE_PNG) {
    $image = imagecreatefrompng($source);
  }

  // Create JPG image
  if(exif_imagetype($source) === IMAGETYPE_JPEG) {
    $image = imagecreatefromjpeg($source);
  }

  if(!$image) {
    return;
  }

  $image_width = imagesx($image);
  $image_height = imagesy($image);
  $total_pixels = $image_width * $image_height;

  print_r(histogram($image, $image_width, $image_height, $total_pixels));
}

function histogram($image, int $image_width, int $image_height, int $total_pixels): array {
  $histogram = [];

  for ($i=0; $i < $image_width; $i++) {
    for ($j=0; $j < $image_height; $j++) {
      $rgb = imagecolorat($image, $i, $j);
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
      $b = $rgb & 0xFF;

      $histogram_v = (int)round(($r + $g + $b) / 3);
      $value = $histogram_v/$total_pixels;
      // echo "histogram_v($histogram_v) / total_pixels($total_pixels) = {$value}\n";

      if(array_key_exists($histogram_v, $histogram)) {
        $histogram[$histogram_v] += $value;
      } else {
        $histogram[$histogram_v] = $value;
      }
    }
  }

  arsort($histogram);
  return $histogram;
}
