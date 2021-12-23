<div align="center">
  <b>Historgram PHP</b>
  <br>
  <br>
  <p>A histogram is an approximate representation of the distribution of numerical data. <a href="https://en.wikipedia.org/wiki/Histogram">Wikipedia<a/></p>
</div>

<br>
<br>

<div align="center">
  <img align="bottom" src="./screenshot-histogram.png" />

</div>

## Usage
Source used in example from <a href="https://unsplash.com/photos/1OtUkD_8svc">Mark Basarab</a>

```php
// Load a source into model
$file = "./temp/desert.jpg";
$histogram = new Histogram($file);

// Custom styles
$options = [
  // Bars max height
  "max_height" => 256,

  // Bars styles
  "bar_styles" => [
    "width" => "4px",
    "background" => "linear-gradient(to bottom, #2380ac, #2f2629)",
    "display" => "inline-block",
    "border-top-right-radius" => "2px",
    "border-top-left-radius" => "2px",
  ],

  // Wrapper styles
  "wrapper_styles" => [
    "display" => "inline-table",
  ]
];

// Display histogram model
$histogram->display($options);
```
And if you want to get a list of bar heights for more customization you could use the below example:

```php
// Get a list of bars from the model with maximum heights of "300"
$bars = $histogram->get_bars($histogram->histogram, 300);
```
