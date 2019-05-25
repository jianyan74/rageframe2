GeoFormat
---------

A geo URI may be used to encode a point on the earth, including altitude. For example, to encode the Google's New York 
office, which is at 40.71872 deg N latitude, 73.98905 deg W longitude, at a point 100 meters above the office, one would 
encode "geo:40.71872,-73.98905,100".

A reader might open a local mapping application like Google Maps to this location and zoom accordingly, or could open a 
link to this location on a mapping web site like Google Maps in the device's web browser.

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\GeoFormat; 

$format = new GeoFormat(['lat' => 1,'lng' => 1, 'altitude' => 20]);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
