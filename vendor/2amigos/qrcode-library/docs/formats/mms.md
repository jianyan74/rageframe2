MmsFormat
---------

Likewise the [SmsFormat](sms.md), there appear to be "mms:" and "MMSTO:" URIs used like "sms:" URIs in practice. We 
assume the format is the same, and that the reader should react similarly to such a URI.

This class, recreates the `MMSTO`.

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\MmsFormat; 

$format = new MmsFormat(['phone' => 657657657, 'msg' => 'test']);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
