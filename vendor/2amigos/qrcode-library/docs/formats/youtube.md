YoutubeFormat
-------------

This is actually an unconfirmed URI that should trigger the Youtube player. 

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\YoutubeFormat; 

$format = new YoutubeFormat(['videoId' => 123456]);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
