<?php

/**
 * Histogram PHP base class
 */
final class Histogram {

  public $histogram;

  public function __construct($source) {
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

    $this->histogram = $this->gram($image);
  }

  /**
   * Display histogram model
   *
   * @return void
   */
  public function display(array $options): void {
    if(empty($this->histogram) || empty($options)) {
      return;
    }

    // Format styles
    $bar_styles = $this->format_styles($options["bar_styles"]);
    $wrapper_styles = $this->format_styles($options["wrapper_styles"]);

    echo "<div style='$wrapper_styles'>";
    // Loop through bars and display them with their styles
    foreach($this->get_bars($this->histogram, $options["max_height"]) as $bar_height) {
      echo "<span style='height:$bar_height;$bar_styles'></span>";
    }
    echo "</div>";
  }

  /**
   * Generate histogram form given source
   *
   * @param  resource $image
   * @return array
   */
  public function gram($image): array {
    // Get image dimensions
    $image_width = imagesx($image);
    $image_height = imagesy($image);
    $total_pixels = $image_width * $image_height;

    $histogram = [];

    for ($i=0; $i < $image_width; $i++) {
      for ($j=0; $j < $image_height; $j++) {
        // Get RGB colors of current pixel
        $rgb = imagecolorat($image, $i, $j);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        $histogram_v = (int) round(($r + $g + $b) / 3);

        if(array_key_exists($histogram_v, $histogram)) {
          $histogram[$histogram_v] += $histogram_v/$total_pixels;
        } else {
          $histogram[$histogram_v] = $histogram_v/$total_pixels;
        }
      }
    }

    arsort($histogram); // Sort the model
    return $histogram;
  }

  /**
   * Get a list of bar heights
   *
   * @param  array       $histogram  gram model
   * @param  int|integer $max_height Maximum bars height
   * @return array
   */
  public function get_bars(array $histogram, int $max_height): array {
    $bars = [];
    $max = 0; // Max value in gram
    $val = 0;
    for ($i=0; $i<255; $i++) {
      if (array_key_exists($i, $histogram) && $histogram[$i] > $max) {
        $max = $histogram[$i];
      }
    }

    for ($i=0; $i<255; $i++) {
      if (array_key_exists($i, $histogram)) {
        $val += $histogram[$i];
        $h = ( $histogram[$i] / $max ) * $max_height;
        $bars[] = $h;
      }
    }

    return $bars;
  }

  /**
   * Generate formatted CSS styles from an array
   *
   * @param  array  $styles_array styles array
   * @return string               CSS styles
   */
  private function format_styles(array $styles_array): string {
    $styles = "";

    foreach ($styles_array as $key => $value) {
      $styles .= "$key:$value;";
    }

    return $styles;
  }
}
