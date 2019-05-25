iCalFormat
---------

Though not observed in any QR Code or reader so far, it is conceivable that iCal format could be used to encode calendar 
events. Readers could add events to the user's calendar in response.

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\iCalFormat; 

$format = new iCalFormat(['summary' => 'test-summary', 'dtStart' => 1260232200, 'dtEnd' => 1260318600]);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
